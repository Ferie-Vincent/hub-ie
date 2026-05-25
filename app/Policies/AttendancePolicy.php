<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['scan-attendance', 'view-applications']);
    }

    public function view(User $user, Attendance $attendance): bool
    {
        return $user->hasAnyPermission(['scan-attendance', 'view-applications']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('scan-attendance');
    }

    public function delete(User $user): bool
    {
        return $user->hasRole('super_admin');
    }
}
