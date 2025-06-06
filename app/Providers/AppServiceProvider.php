<?php

namespace App\Providers;

use App\Models\Client;
use App\Policies\Api\ClientPolicy;
use App\Repositories\ClientRepository;
use App\Repositories\ClientRepositoryEloquent;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Prettus\Repository\Providers\RepositoryServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected array $policies = [
        Client::class => ClientPolicy::class,
    ];

    public function register(): void
    {
        $this->registerPolicies();
        $this->registerRepositories();
    }

    public function registerPolicies(): void
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }

    private function registerRepositories(): void
    {
        $this->app->register(RepositoryServiceProvider::class);
    }

    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        $this->app->bind(ClientRepository::class, ClientRepositoryEloquent::class);
    }
}
