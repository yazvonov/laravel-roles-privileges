<?php

namespace Yazvonov\LaravelRolesPrivileges\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Yazvonov\LaravelRolesPrivileges\Models\Privilege
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Yazvonov\LaravelRolesPrivileges\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|Privilege newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Privilege newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Privilege query()
 * @method static \Illuminate\Database\Eloquent\Builder|Privilege whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Privilege whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Privilege whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Privilege whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Privilege whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Privilege extends Model
{
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
