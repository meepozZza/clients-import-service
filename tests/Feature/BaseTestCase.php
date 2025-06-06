<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class BaseTestCase extends TestCase
{
    use DatabaseMigrations;

    protected function makeActingAsUser(): User
    {
        $password = 'password';

        /** @var User $user */
        $user = User::factory()->create();

        $authResponse = $this->post('api/auth/login', [
            'login' => $user->email,
            'password' => $password,
        ]);

        $this->actingAs($user)->withToken($authResponse->json('token'));

        return $user;
    }
}
