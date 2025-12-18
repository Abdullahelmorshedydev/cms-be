<?php

namespace App\Repositories;

use App\Enums\StatusEnum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BasicRepository
{
    public function model(): string
    {
        return User::class;
    }

    public function getVerifiedUser($password, $user): User|null|string
    {
        $type = filter_var($user, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $adminUser = $this->model->firstWhere($type, $user);
        if ($adminUser && Hash::check($password, $adminUser->password)) {
            if ($adminUser->email_verified_at) {
                if ($adminUser->is_active != StatusEnum::ACTIVE) {
                    return "not-active";
                }
                return $adminUser;
            } else {
                return "not-verified";
            }
        }
        return null;
    }
}
