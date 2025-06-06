<?php

namespace App\Policies\Api;

use App\Models\User;

class ClientPolicy
{
    public function index(User $user): bool
    {
        return true;
    }

    public function import(User $user): bool
    {
        return true;
    }
}
