<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use App\Services\FineService;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpotFineBalanceDoublingTest extends TestCase
{
    use RefreshDatabase;

    public function test_spot_fine_doubles_only_remaining_unpaid_balance(): void
    {
        $treasurer = User::factory()->create(['role' => 'treasurer']);
        $user = User::factory()->create(['role' => 'member']);
        $member = Member::create(['user_id' => $user->id, 'member_number' => 'MEM-301']);

        $fineService = new FineService();
        $paymentService = new PaymentService();

        // 1. Create Spot Fine = Rs 50
        $fine = $fineService->createSpotFine($member, 'Late arrival', 50.00, $treasurer);
        $this->assertEquals(50.00, $fine->remaining_amount);

        // 2. Member pays Rs 40 partial payment
        $paymentService->recordExplicitPayment($member, [], [$fine->id => 40.00], 'cash', null, null, $treasurer);

        $fine->refresh();
        $this->assertEquals(40.00, $fine->paid_amount);
        $this->assertEquals(10.00, $fine->remaining_amount);

        // 3. Trigger doubling cycle: Remaining Rs 10 doubles to Rs 20 (NOT original Rs 50 to Rs 100)
        $fineService->doubleSpotFine($fine, $treasurer);

        $fine->refresh();
        $this->assertEquals(20.00, $fine->remaining_amount);
        $this->assertEquals(1, $fine->doubling_count);
    }
}
