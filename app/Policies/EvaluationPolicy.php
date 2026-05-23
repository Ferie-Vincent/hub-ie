<?php

namespace App\Policies;

use App\Models\Evaluation;
use App\Models\User;

class EvaluationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermissionTo(['evaluate-applications', 'view-applications']);
    }

    public function view(User $user, Evaluation $evaluation): bool
    {
        if ($user->hasRole('committee_member')) {
            return $evaluation->evaluator_id === $user->id;
        }

        return $user->hasAnyPermissionTo(['evaluate-applications', 'view-applications']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('evaluate-applications');
    }

    public function update(User $user, Evaluation $evaluation): bool
    {
        return $user->hasPermissionTo('evaluate-applications')
            && $evaluation->evaluator_id === $user->id;
    }

    public function delete(User $user): bool
    {
        return $user->hasRole('super_admin');
    }
}
