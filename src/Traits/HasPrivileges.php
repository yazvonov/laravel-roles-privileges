<?php

namespace Yazvonov\LaravelRolesPrivileges\Traits;

use Yazvonov\LaravelRolesPrivileges\Models\Privilege;
use Yazvonov\LaravelRolesPrivileges\Models\Role;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\Yazvonov\LaravelRolesPrivileges\Models\Privilege[] $privileges
 */
trait HasPrivileges
{
    use HasRoles;

    public function privileges()
    {
        return $this->belongsToMany(Role::class)
            ->leftJoin('privilege_role', 'privilege_role.role_id', 'roles.id')
            ->leftJoin('privileges', 'privileges.id', 'privilege_role.privilege_id')
            ->select('privileges.*');
    }

    public function hasPrivilege($title)
    {
        return !!$this->privileges->firstWhere('title', $title);
    }
}
