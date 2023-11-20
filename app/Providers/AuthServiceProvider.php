<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;

//use Illuminate\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function ($user) {

            if ($user->status === 'ativo' AND $user->nv_acesso === 'admin'){
                return true;
            }else{
                return false;
            }
        });
        
        Gate::define('almox', function ($user) {

            if ($user->status === 'ativo' && $user->nv_acesso === 'admin' || $user->nv_acesso === 'almox'){
                return true;
            }else{
                return false;
            }

            
        });

        Gate::define('apoio', function ($user) {

            if ($user->status === 'ativo' && $user->nv_acesso === 'admin' || $user->nv_acesso === 'apoio'){
                return true;
            }else{
                return false;
            }

            
        });

        Gate::define('tecnico', function ($user) {

            if ($user->status === 'ativo' && $user->nv_acesso === 'admin' || $user->nv_acesso === 'tecnico'){
                return true;
            }else{
                return false;
            }

            
        });

        Gate::define('supervisor', function ($user) {

            if ($user->status === 'ativo' && $user->nv_acesso === 'admin' || $user->nv_acesso === 'supervisor'){
                return true;
            }else{
                return false;
            }

            
        });

        Gate::define('controle', function ($user) {

            if ($user->status === 'ativo' && $user->nv_acesso === 'admin' || $user->nv_acesso === 'controle'){
                return true;
            }else{
                return false;
            }

            
        });

        Gate::define('controle_supervisor', function ($user) {

            if ($user->status === 'ativo' && $user->nv_acesso === 'admin' || $user->nv_acesso === 'controle' || $user->nv_acesso === 'supervisor'){
                return true;
            }else{
                return false;
            }

            
        });

        Gate::define('almox_controle_supervisor', function ($user) {

            if ($user->status === 'ativo' AND $user->nv_acesso === 'admin' || $user->nv_acesso === 'almox' || $user->nv_acesso === 'controle' || $user->nv_acesso === 'supervisor'){
                return true;
            }else{
                return false;
            }

            
        });

        Gate::define('almox_controle', function ($user) {

            if ($user->status === 'ativo' AND $user->nv_acesso === 'admin' || $user->nv_acesso === 'almox' || $user->nv_acesso === 'controle'){
                return true;
            }else{
                return false;
            }

            
        });

        Gate::define('tecnico_controle', function ($user) {

            if ($user->status === 'ativo' AND $user->nv_acesso === 'admin' || $user->nv_acesso === 'tecnico' || $user->nv_acesso === 'controle'){
                return true;
            }else{
                return false;
            }

            
        });

        Gate::define('controle_supervisor_apoio', function ($user) {

            if ($user->status === 'ativo' && $user->nv_acesso === 'admin' || $user->nv_acesso === 'controle' || $user->nv_acesso === 'supervisor' || $user->nv_acesso === 'apoio'){
                return true;
            }else{
                return false;
            }

            
        });

        Gate::define('controle_apoio', function ($user) {

            if ($user->status === 'ativo' && $user->nv_acesso === 'admin' || $user->nv_acesso === 'controle' || $user->nv_acesso === 'apoio'){
                return true;
            }else{
                return false;
            }

            
        });

        Gate::define('tecnico_controle_supervisor_apoio', function ($user) {

            if ($user->status === 'ativo' AND $user->nv_acesso === 'admin' || $user->nv_acesso === 'tecnico' || $user->nv_acesso === 'controle' || $user->nv_acesso === 'supervisor' || $user->nv_acesso === 'apoio'){
                return true;
            }else{
                return false;
            }

            
        });


        Gate::define('ativo', function ($user) {
            
            if ($user->status === 'ativo'){
                return true;
            }else{
                return false;
            }

            
        });
    }
}
