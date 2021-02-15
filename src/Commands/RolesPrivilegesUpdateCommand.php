<?php

namespace Yazvonov\LaravelRolesPrivileges\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Yazvonov\LaravelRolesPrivileges\Models\Privilege;
use Yazvonov\LaravelRolesPrivileges\Models\Role;

class RolesPrivilegesUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles-privileges:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update privileges and roles from config.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $this->updatePrivileges();

        $this->updateRoles();
    }

    private function updatePrivileges()
    {
        $addedQty = 0;
        $updatedQty = 0;
        $deletedQty = 0;

        $privileges = Privilege::all()->keyBy('title');
        $privilegesConfig = config('privileges');

        // Add or Update
        foreach ($privilegesConfig as $title => $description) {
            if (!isset($privileges[$title])) {
                $privilege = new Privilege();
                $privilege->title = $title;
                $privilege->description = $description;

                if (!$privilege->save()) {
                    abort(559,'Cannot add privilege "' . $title . '"');
                }

                $addedQty++;
            } else {
                /** @var Privilege $privilege */
                $privilege = $privileges[$title];

                if ($description != $privilege->description) {
                    $privilege->description = $description;

                    if (!$privilege->save()) {
                        abort(559,'Cannot update privilege "' . $title . '"');
                    }

                    $updatedQty++;
                }
            }
        }

        // Delete
        foreach ($privileges as $title => $privilege) {
            if (!isset($privilegesConfig[$title])) {
                if (!$privilege->delete()) {
                    abort(559, 'Cannot delete privilege "' . $title . '"');
                }

                $deletedQty++;
            }
        }

        $this->info('Privileges added: ' . $addedQty);
        $this->info('Privileges updated: ' . $updatedQty);
        $this->info('Privileges deleted: ' . $deletedQty);
        $this->info('Privileges total: ' . count($privilegesConfig));

        return true;
    }

    private function updateRoles()
    {
        $addedQty = 0;
        $updatedQty = 0;
        $deletedQty = 0;

        $roles = Role::all()->keyBy('title');
        $rolesConfig = config('roles');

        // Add or Update
        foreach ($rolesConfig as $title => $params) {
            $description = $params['description'];

            if (!isset($roles[$title])) {
                $role = new Role();
                $role->title = $title;
                $role->description = $description;

                if (!$role->save()) {
                    abort(559,'Cannot add role "' . $title . '"');
                }

                $addedQty++;
            } else {
                /** @var Privilege $privilege */
                $role = $roles[$title];

                if ($description != $role->description) {
                    $role->description = $description;

                    if (!$role->save()) {
                        abort(559,'Cannot update role "' . $title . '"');
                    }

                    $updatedQty++;
                }
            }

            $ids = Privilege::select('id')
                ->when(is_array($params['privileges']), function (Builder $query) use ($params) {
                    $query->whereIn('title', $params['privileges']);
                })
                ->pluck('id');

            $role->privileges()->sync($ids);
        }

        // Delete
        foreach ($roles as $title => $role) {
            if (!isset($rolesConfig[$title])) {
                if (!$role->delete()) {
                    abort(559, 'Cannot delete role "' . $title . '"');
                }

                $deletedQty++;
            }
        }

        $this->info('Roles added: ' . $addedQty);
        $this->info('Roles updated: ' . $updatedQty);
        $this->info('Roles deleted: ' . $deletedQty);
        $this->info('Roles total: ' . count($rolesConfig));

        return true;
    }
}
