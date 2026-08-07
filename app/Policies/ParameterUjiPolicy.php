<?php

namespace App\Policies;

use App\Models\ParameterUji;
use App\Models\User;
use App\Policies\Concerns\ChecksDomainAccess;
use Illuminate\Auth\Access\HandlesAuthorization;

class ParameterUjiPolicy
{
    use HandlesAuthorization, ChecksDomainAccess;

    public function __construct()
    {
        $this->kodeModul = 'parameter_uji';
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ParameterUji $parameterUji): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->role->nama_role === \App\Enums\PeranPengguna::ADMIN_LAB->value) return true;
        return $this->hasFullAccess($user, 'full');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ParameterUji $parameterUji): bool
    {
        if ($user->role->nama_role === \App\Enums\PeranPengguna::ADMIN_LAB->value) return true;
        return $this->hasFullAccess($user, 'full');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ParameterUji $parameterUji): bool
    {
        if ($user->role->nama_role === \App\Enums\PeranPengguna::ADMIN_LAB->value) return true;
        return $this->hasFullAccess($user, 'full');
    }
}
