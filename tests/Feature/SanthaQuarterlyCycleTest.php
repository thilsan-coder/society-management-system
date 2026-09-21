<?php

namespace Tests\Feature;

use App\Models\Fine;
use App\Models\Member;
use App\Models\User;
use App\Services\SanthaService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SanthaQuarterlyCycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_late_fine_during_first_three_months_of_cycle(): void
    {
        $user = User::factory()->create(['role' => 'member']);
        $member = Member::create(['user_id' => $user->id, 'member_number' => 'MEM-201', 'join_date' => '2026-01-01']);

        $service = new SanthaService();

        // Simulate Month 3 (March 2026)
        $march = Carbon::create(2026, 3, 15);
        $service->generateMonthlySanthasForAllMembers($march);

        // Santhas for Jan, Feb, Mar created
        $this->assertEquals(3, $member->santhas()->count());

        // Zero late fines generated during first 3 months
        $lateFines = Fine::where('member_id', $member->id)->where('fine_type', 'late_santha')->count();
        $this->assertEquals(0, $lateFines);
    }

    public function test_rs_100_late_fine_added_when_entering_month_4_for_unpaid_cycle(): void
    {
        $user = User::factory()->create(['role' => 'member']);
        $member = Member::create(['user_id' => $user->id, 'member_number' => 'MEM-202', 'join_date' => '2026-01-01']);

        $service = new SanthaService();

        // Simulate Month 4 (April 2026) while Cycle 1 (Jan-Mar = Rs 900) remains unpaid
        $april = Carbon::create(2026, 4, 15);
        $service->generateMonthlySanthasForAllMembers($april);

        // Rs 100 late fine should be generated
        $lateFine = Fine::where('member_id', $member->id)->where('fine_type', 'late_santha')->first();
        $this->assertNotNull($lateFine);
        $this->assertEquals(100.00, $lateFine->remaining_amount);
    }
}
