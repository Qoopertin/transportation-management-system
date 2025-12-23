<?php

namespace App\Providers;

use App\Models\Load;
use App\Policies\LoadPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Load::class => LoadPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
