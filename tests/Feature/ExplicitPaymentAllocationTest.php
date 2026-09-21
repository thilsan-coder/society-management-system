<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use App\Services\FineService;
use App\Services\PaymentService;
use App\Services\SanthaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExplicitPaymentAllocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_explicit_payment_allocation_updates_balances_and_generates_receipt(): void
    {
        $treasurer = User::factory()->create(['role' => 'treasurer']);
        $user = User::factory()->create(['role' => 'member']);
        $member = Member::create(['user_id' => $user->id, 'member_number' => 'MEM-401']);

        $santhaService = new SanthaService();
        $fineService = new FineService();
        $paymentService = new PaymentService();

        $santha = $santhaService->ensureSanthaForMember($member, 2026, 1);
        $fine = $fineService->createSpotFine($member, 'Late fee', 100.00, $treasurer);

        // Record explicit payment: Rs 300 for Santha, Rs 50 for Fine
        $payment = $paymentService->recordExplicitPayment(
            $member,
            [$santha->id => 300.00],
            [$fine->id => 50.00],
            'cash',
            'TXN-9988',
            'Full Santha + Partial Fine',
            $treasurer
        );

        $this->assertNotNull($payment);
        $this->assertStringStartsWith('RCT-', $payment->receipt_number);
        $this->assertEquals(350.00, $payment->total_amount);

        $santha->refresh();
        $fine->refresh();

        $this->assertEquals('paid', $santha->status);
        $this->assertEquals(50.00, $fine->remaining_amount);
        $this->assertEquals('partially_paid', $fine->status);
    }
}
