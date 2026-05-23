<?php

namespace App\Policies;

use App\Models\Partner;
use App\Models\User;

class PartnerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermissionTo(['manage-content', 'view-applications']);
    }

    public function view(User $user, Partner $partner): bool
    {
        return $user->hasAnyPermissionTo(['manage-content', 'view-applications']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage-content');
    }

    public function update(User $user, Partner $partner): bool
    {
        return $user->hasPermissionTo('manage-content');
    }

    public function delete(User $user, Partner $partner): bool
    {
        return $user->hasPermissionTo('manage-content');
    }
}
