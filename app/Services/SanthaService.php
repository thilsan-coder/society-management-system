<?php

namespace App\Services;

use App\Models\Fine;
use App\Models\Member;
use App\Models\Santha;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SanthaService
{
    /**
     * Generate or fetch Santha for a specific member, year, and month (Rs 300/mo).
     */
    public function ensureSanthaForMember(Member $member, int $year, int $month): Santha
    {
        $dueDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        return Santha::firstOrCreate(
            [
                'member_id' => $member->id,
                'year' => $year,
                'month' => $month,
            ],
            [
                'amount' => 300.00,
                'paid_amount' => 0.00,
                'due_date' => $dueDate,
                'status' => 'unpaid',
            ]
        );
    }

    /**
     * Check and generate Santha records for all active members up to current month.
     */
    public function generateMonthlySanthasForAllMembers(?Carbon $now = null): void
    {
        $now = $now ?? now();
        $members = Member::all();

        foreach ($members as $member) {
            $joinDate = $member->join_date ? Carbon::parse($member->join_date) : $now->copy()->startOfYear();
            $cursor = $joinDate->copy()->startOfMonth();

            while ($cursor->lessThanOrEqualTo($now->copy()->startOfMonth())) {
                $this->ensureSanthaForMember($member, $cursor->year, $cursor->month);
                $cursor->addMonth();
            }

            $this->evaluateLateFinesForMember($member, $now);
        }
    }

    /**
     * Evaluate late Santha fines for a member based on actual payment state.
     * Rule: No late fine during Months 1-3 of a 3-month cycle (Jan-Mar, Apr-Jun, Jul-Sep, Oct-Dec).
     * If the cycle balance is unpaid when entering Month 4 (or Month 5, etc.), charge Rs. 100 per unpaid overdue month.
     */
    public function evaluateLateFinesForMember(Member $member, ?Carbon $now = null): void
    {
        $now = $now ?? now();
        $currentYear = $now->year;
        $currentMonth = $now->month;

        // Cycles in a year:
        // Cycle 1: Months 1, 2, 3 -> Settlement at end of Month 3
        // Cycle 2: Months 4, 5, 6 -> Settlement at end of Month 6
        // Cycle 3: Months 7, 8, 9 -> Settlement at end of Month 9
        // Cycle 4: Months 10, 11, 12 -> Settlement at end of Month 12

        $santhas = Santha::where('member_id', $member->id)
            ->where(function ($q) use ($currentYear, $currentMonth) {
                $q->where('year', '<', $currentYear)
                  ->orWhere(function ($q2) use ($currentYear, $currentMonth) {
                      $q2->where('year', $currentYear)->where('month', '<=', $currentMonth);
                  });
            })
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Group santhas by 3-month cycle
        $cycles = [];
        foreach ($santhas as $santha) {
            $cycleNumber = (int) ceil($santha->month / 3);
            $cycleKey = "{$santha->year}-C{$cycleNumber}";

            if (!isset($cycles[$cycleKey])) {
                $cycles[$cycleKey] = [
                    'year' => $santha->year,
                    'cycle_number' => $cycleNumber,
                    'months' => [],
                    'total_amount' => 0.0,
                    'total_paid' => 0.0,
                    'cycle_end_month' => $cycleNumber * 3,
                ];
            }

            $cycles[$cycleKey]['months'][] = $santha;
            $cycles[$cycleKey]['total_amount'] += $santha->amount;
            $cycles[$cycleKey]['total_paid'] += $santha->paid_amount;
        }

        foreach ($cycles as $cycleKey => $cycle) {
            $cycleYear = $cycle['year'];
            $cycleEndMonth = $cycle['cycle_end_month'];

            // Calculate the month following the cycle end (Month 4 of the cycle context)
            $penaltyStartYear = $cycleYear;
            $penaltyStartMonth = $cycleEndMonth + 1;
            if ($penaltyStartMonth > 12) {
                $penaltyStartMonth = 1;
                $penaltyStartYear++;
            }

            $penaltyStartDate = Carbon::createFromDate($penaltyStartYear, $penaltyStartMonth, 1)->startOfMonth();

            // Only apply late fine if we have reached or passed the penalty start month
            if ($now->greaterThanOrEqualTo($penaltyStartDate)) {
                $cycleOutstanding = $cycle['total_amount'] - $cycle['total_paid'];

                if ($cycleOutstanding > 0) {
                    // For each month from penaltyStartMonth up to current month (or end of cycle grace),
                    // generate a Rs 100 late fine if not already generated.
                    $fineCursor = $penaltyStartDate->copy();

                    while ($fineCursor->lessThanOrEqualTo($now->copy()->startOfMonth())) {
                        $reason = "Late Santha Fine for Cycle {$cycle['cycle_number']} ({$cycleYear}) - Month {$fineCursor->format('F Y')}";
                        
                        // Check duplicate
                        $exists = Fine::where('member_id', $member->id)
                            ->where('fine_type', 'late_santha')
                            ->where('reason', $reason)
                            ->exists();

                        if (!$exists) {
                            Fine::create([
                                'member_id' => $member->id,
                                'fine_type' => 'late_santha',
                                'reason' => $reason,
                                'original_amount' => 100.00,
                                'paid_amount' => 0.00,
                                'remaining_amount' => 100.00,
                                'doubling_count' => 0,
                                'fine_date' => $fineCursor->copy()->startOfMonth()->toDateString(),
                                'status' => 'unpaid',
                                'created_by' => null,
                            ]);
                        }

                        $fineCursor->addMonth();
                    }
                }
            }
        }
    }
}
