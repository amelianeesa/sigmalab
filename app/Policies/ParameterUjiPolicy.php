<?php

namespace App\Policies;

use App\Models\ParameterUji;
use App\Models\User;
use App\Enums\PeranPengguna;
use App\Policies\Concerns\ChecksDomainAccess;
use Illuminate\Auth\Access\HandlesAuthorization;

class ParameterUjiPolicy
{
    use HandlesAuthorization, ChecksDomainAccess;

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
        return $this->hasFullAccess($user, [
            PeranPengguna::KOORDINATOR_LAB->value,
            PeranPengguna::ADMIN_APLIKASI->value,
            PeranPengguna::DEVELOPER->value,
        ]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ParameterUji $parameterUji): bool
    {
        return $this->hasFullAccess($user, [
            PeranPengguna::KOORDINATOR_LAB->value,
            PeranPengguna::ADMIN_APLIKASI->value,
            PeranPengguna::DEVELOPER->value,
        ]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ParameterUji $parameterUji): bool
    {
        return $this->hasFullAccess($user, [
            PeranPengguna::KOORDINATOR_LAB->value,
            PeranPengguna::ADMIN_APLIKASI->value,
            PeranPengguna::DEVELOPER->value,
        ]);
    }
}
