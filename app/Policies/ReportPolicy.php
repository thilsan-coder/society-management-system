<?php

namespace App\Policies;

use App\Models\MonthlyReport;
use App\Models\User;

class ReportPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, MonthlyReport $report): bool
    {
        if ($user->hasRole(['president', 'secretary', 'treasurer'])) {
            return true;
        }

        return in_array($report->status, ['approved', 'published']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['president', 'secretary', 'treasurer']);
    }

    public function update(User $user, MonthlyReport $report): bool
    {
        return $user->hasRole(['president', 'secretary', 'treasurer']) && in_array($report->status, ['draft', 'rejected']);
    }

    public function approve(User $user, MonthlyReport $report): bool
    {
        return $user->isPresident();
    }
}
