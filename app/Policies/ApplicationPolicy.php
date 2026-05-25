<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['view-applications', 'evaluate-applications']);
    }

    public function view(User $user, Application $application): bool
    {
        if ($user->hasRole('candidate')) {
            return $application->user_id === $user->id;
        }

        return $user->hasAnyPermission(['view-applications', 'evaluate-applications']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('candidate');
    }

    public function update(User $user, Application $application): bool
    {
        if ($user->hasRole('candidate')) {
            return $application->user_id === $user->id && $application->status->value === 'draft';
        }

        return $user->hasPermissionTo('view-applications');
    }

    public function markEligible(User $user): bool
    {
        return $user->hasPermissionTo('mark-eligible');
    }

    public function evaluate(User $user): bool
    {
        return $user->hasPermissionTo('evaluate-applications');
    }

    public function accept(User $user): bool
    {
        return $user->hasPermissionTo('accept-applications');
    }

    public function reject(User $user): bool
    {
        return $user->hasPermissionTo('reject-applications');
    }

    public function delete(User $user, Application $application): bool
    {
        return $user->hasRole('super_admin');
    }
}
