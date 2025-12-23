<?php

namespace App\Policies;

use App\Models\Load;
use App\Models\User;

class LoadPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view loads');
    }

    public function view(User $user, Load $load): bool
    {
        if ($user->hasRole('driver')) {
            return $load->assigned_driver_id === $user->id;
        }
        
        return $user->hasPermissionTo('view loads');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create loads');
    }

    public function update(User $user, Load $load): bool
    {
        if ($user->hasRole('driver')) {
            return $load->assigned_driver_id === $user->id;
        }
        
        return $user->hasPermissionTo('update loads');
    }

    public function delete(User $user, Load $load): bool
    {
        return $user->hasPermissionTo('delete loads');
    }
}
