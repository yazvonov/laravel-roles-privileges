<?php

namespace Yazvonov\LaravelRolesPrivileges\Traits;

use Yazvonov\LaravelRolesPrivileges\Models\Role;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\Yazvonov\LaravelRolesPrivileges\Models\Role[] $roles
 */
trait HasRoles
{
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($title)
    {
        return !!$this->roles->firstWhere('title', $title);
    }
}
