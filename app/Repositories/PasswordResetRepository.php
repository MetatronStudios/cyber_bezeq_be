<?php

namespace App\Repositories;

use App\PasswordReset;

class PasswordResetRepository extends ParentRepository
{
    public function __construct()
    {
        $this->model = new PasswordReset();
    }

    public function updateOrCreate($email)
    {
        return $this->model->updateOrCreate(
            ['email' => $email],
            ['email' => $email, 'token' => str_random(60)]
        );
    }

    public function getByEmailAndToken($token)
    {
        return $this->model->where([
            ['token', $token]
        ])->first();
    }
}
