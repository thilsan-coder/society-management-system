<?php

namespace App\Services;

use App\Models\Fine;
use App\Models\Member;
use App\Models\Payment;
use App\Models\PaymentItem;
use App\Models\Santha;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class PaymentService
{
    public function generateReceiptNumber(): string
    {
        $prefix = 'RCT-' . date('Ym') . '-';
        $latestPayment = Payment::where('receipt_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$latestPayment) {
            return $prefix . '0001';
        }

        $numberPart = (int) substr($latestPayment->receipt_number, strlen($prefix));
        $nextNumber = str_pad((string) ($numberPart + 1), 4, '0', STR_PAD_LEFT);

        return $prefix . $nextNumber;
    }

    /**
     * Record explicit payment against specific Santhas and Fines.
     *
     * @param Member $member
     * @param array $santhaAllocations [santha_id => amount]
     * @param array $fineAllocations [fine_id => amount]
     * @param string $paymentMethod
     * @param string|null $referenceNumber
     * @param string|null $notes
     * @param User $treasurer
     * @return Payment
     */
    public function recordExplicitPayment(
        Member $member,
        array $santhaAllocations,
        array $fineAllocations,
        string $paymentMethod,
        ?string $referenceNumber,
        ?string $notes,
        User $treasurer
    ): Payment {
        return DB::transaction(function () use ($member, $santhaAllocations, $fineAllocations, $paymentMethod, $referenceNumber, $notes, $treasurer) {
            $totalAmount = 0.0;
            $itemsToCreate = [];

            // Process Santha allocations
            foreach ($santhaAllocations as $santhaId => $amount) {
                $payAmount = (float) $amount;
                if ($payAmount <= 0) continue;

                $santha = Santha::where('id', $santhaId)->where('member_id', $member->id)->lockForUpdate()->first();
                if (!$santha) {
                    throw new Exception("Santha record #{$santhaId} not found for member.");
                }

                $maxPayable = $santha->amount - $santha->paid_amount;
                if ($payAmount > $maxPayable + 0.01) {
                    throw new Exception("Allocation of Rs {$payAmount} exceeds remaining due (Rs {$maxPayable}) for Santha {$santha->month}/{$santha->year}.");
                }

                $newPaid = $santha->paid_amount + $payAmount;
                $santha->paid_amount = $newPaid;
                $santha->status = ($newPaid >= $santha->amount) ? 'paid' : 'partially_paid';
                $santha->save();

                $totalAmount += $payAmount;
                $itemsToCreate[] = [
                    'payable_type' => Santha::class,
                    'payable_id' => $santha->id,
                    'amount' => $payAmount,
                ];
            }

            // Process Fine allocations
            foreach ($fineAllocations as $fineId => $amount) {
                $payAmount = (float) $amount;
                if ($payAmount <= 0) continue;

                $fine = Fine::where('id', $fineId)->where('member_id', $member->id)->lockForUpdate()->first();
                if (!$fine) {
                    throw new Exception("Fine record #{$fineId} not found for member.");
                }

                if ($payAmount > $fine->remaining_amount + 0.01) {
                    throw new Exception("Allocation of Rs {$payAmount} exceeds remaining balance (Rs {$fine->remaining_amount}) for Fine #{$fineId}.");
                }

                $newPaid = $fine->paid_amount + $payAmount;
                $newRemaining = max(0.0, $fine->remaining_amount - $payAmount);

                $fine->paid_amount = $newPaid;
                $fine->remaining_amount = $newRemaining;
                $fine->status = ($newRemaining <= 0) ? 'paid' : 'partially_paid';
                $fine->save();

                $totalAmount += $payAmount;
                $itemsToCreate[] = [
                    'payable_type' => Fine::class,
                    'payable_id' => $fine->id,
                    'amount' => $payAmount,
                ];
            }

            if ($totalAmount <= 0) {
                throw new Exception("Total payment amount must be greater than zero.");
            }

            $hasSantha = !empty($santhaAllocations);
            $hasFine = !empty($fineAllocations);
            $paymentType = ($hasSantha && $hasFine) ? 'mixed' : ($hasSantha ? 'santha' : 'fine');

            $payment = Payment::create([
                'member_id' => $member->id,
                'receipt_number' => $this->generateReceiptNumber(),
                'payment_type' => $paymentType,
                'total_amount' => $totalAmount,
                'payment_date' => now()->toDateString(),
                'payment_method' => $paymentMethod,
                'reference_number' => $referenceNumber,
                'notes' => $notes,
                'treasurer_id' => $treasurer->id,
            ]);

            foreach ($itemsToCreate as $itemData) {
                PaymentItem::create([
                    'payment_id' => $payment->id,
                    'payable_type' => $itemData['payable_type'],
                    'payable_id' => $itemData['payable_id'],
                    'amount' => $itemData['amount'],
                ]);
            }

            AuditLogService::log(
                $treasurer,
                "Recorded Payment {$payment->receipt_number} (Rs {$totalAmount}) for Member {$member->member_number}",
                Payment::class,
                $payment->id
            );

            return $payment;
        }
    }
}
