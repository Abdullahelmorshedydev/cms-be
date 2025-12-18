<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProfileService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected MediaService $mediaService
    ) {
    }

    /**
     * Update admin profile
     */
    public function updateAdminProfile(array $data, User $user): bool
    {
        try {
            DB::beginTransaction();

            // Fields to update for admin
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'country_code' => $data['country_code'],
                'address' => $data['address'] ?? null,
                'bio' => $data['bio'] ?? null,
                'job_title' => $data['job_title'] ?? null,
            ];

            $this->userRepository->update($updateData, $user->id);

            // Handle image upload
            if (isset($data['image']) && is_file($data['image'])) {
                $updatedUser = $this->userRepository->findOneBy(['id' => $user->id]);
                $this->mediaService->uploadImage($data['image'], $updatedUser, $updatedUser->name);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin Profile Update Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update profile based on user type
     */
    public function updateProfile(array $data, User $user): bool
    {
        if ($user->isAdmin())
            return $this->updateAdminProfile($data, $user);

        return false;
    }
}
