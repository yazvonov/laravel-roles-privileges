<?php

namespace Yazvonov\LaravelRolesPrivileges;

use Illuminate\Support\ServiceProvider;
use Yazvonov\LaravelRolesPrivileges\Commands\RolesPrivilegesUpdateCommand;
use Gate;

class RolesPrivilegesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/config/roles.php', 'roles');
        $this->mergeConfigFrom(__DIR__.'/config/privileges.php', 'privileges');

        $this->loadMigrationsFrom(__DIR__.'/migrations');

        foreach (config('privileges') as $privilege => $description) {
            Gate::define($privilege, function ($user) use ($privilege) {
                return $user->hasPrivilege($privilege);
            });
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                RolesPrivilegesUpdateCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/config/roles.php' => config_path('roles.php'),
                __DIR__.'/config/privileges.php' => config_path('privileges.php'),
            ], ['config', 'roles-privileges']);

            $this->publishes([
                __DIR__.'/migrations' => database_path('migrations')
            ], ['migrations', 'roles-privileges']);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
