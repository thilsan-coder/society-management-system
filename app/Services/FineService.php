<?php

namespace App\Services;

use App\Models\Fine;
use App\Models\Meeting;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FineService
{
    public function createSpotFine(Member $member, string $reason, float $amount, User $creator): Fine
    {
        $fine = Fine::create([
            'member_id' => $member->id,
            'fine_type' => 'spot',
            'reason' => $reason,
            'original_amount' => $amount,
            'paid_amount' => 0.00,
            'remaining_amount' => $amount,
            'doubling_count' => 0,
            'fine_date' => now()->toDateString(),
            'status' => 'unpaid',
            'created_by' => $creator->id,
        ]);

        AuditLogService::log($creator, "Created Spot Fine #{$fine->id} (Rs {$amount}) for Member {$member->member_number}", Fine::class, $fine->id);

        return $fine;
    }

    public function doubleSpotFine(Fine $fine, User $actor): Fine
    {
        if ($fine->fine_type !== 'spot' || $fine->status === 'paid' || $fine->remaining_amount <= 0) {
            return $fine;
        }

        return DB::transaction(function () use ($fine, $actor) {
            $currentRemaining = (float) $fine->remaining_amount;
            $newRemaining = $currentRemaining * 2;

            $fine->remaining_amount = $newRemaining;
            $fine->doubling_count += 1;
            $fine->save();

            AuditLogService::log(
                $actor,
                "Doubled Spot Fine #{$fine->id}: remaining balance changed from Rs {$currentRemaining} to Rs {$newRemaining}",
                Fine::class,
                $fine->id,
                ['previous_remaining' => $currentRemaining, 'new_remaining' => $newRemaining, 'doubling_count' => $fine->doubling_count]
            );

            return $fine;
        });
    }

    public function createDefaultFine(Member $member, string $reason, float $amount, ?string $description, User $creator): Fine
    {
        $fine = Fine::create([
            'member_id' => $member->id,
            'fine_type' => 'default',
            'reason' => $reason . ($description ? " ({$description})" : ""),
            'original_amount' => $amount,
            'paid_amount' => 0.00,
            'remaining_amount' => $amount,
            'doubling_count' => 0,
            'fine_date' => now()->toDateString(),
            'status' => 'unpaid',
            'created_by' => $creator->id,
        ]);

        AuditLogService::log($creator, "Created Default Fine #{$fine->id} (Rs {$amount}) for Member {$member->member_number}", Fine::class, $fine->id);

        return $fine;
    }

    public function createAbsenceFine(Member $member, Meeting $meeting, float $amount, User $creator): ?Fine
    {
        // Check duplicate absence fine for same meeting & member
        $exists = Fine::where('member_id', $member->id)
            ->where('meeting_id', $meeting->id)
            ->where('fine_type', 'absence')
            ->exists();

        if ($exists) {
            return null;
        }

        $fine = Fine::create([
            'member_id' => $member->id,
            'fine_type' => 'absence',
            'reason' => "Meeting Absence Fine: {$meeting->title} (" . Carbon::parse($meeting->meeting_date)->format('d/m/Y') . ")",
            'original_amount' => $amount,
            'paid_amount' => 0.00,
            'remaining_amount' => $amount,
            'doubling_count' => 0,
            'meeting_id' => $meeting->id,
            'fine_date' => $meeting->meeting_date,
            'status' => 'unpaid',
            'created_by' => $creator->id,
        ]);

        AuditLogService::log($creator, "Generated Meeting Absence Fine #{$fine->id} for Member {$member->member_number} (Meeting: {$meeting->title})", Fine::class, $fine->id);

        return $fine;
    }
}
