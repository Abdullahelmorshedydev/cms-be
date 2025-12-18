<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use App\Traits\MediaHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProfileService
{
    use MediaHandler;

    public function __construct(
        protected UserRepository $userRepository
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
                'gender' => $data['gender'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'address' => $data['address'] ?? null,
                'bio' => $data['bio'] ?? null,
                'job_title' => $data['job_title'] ?? null,
            ];

            $this->userRepository->update($updateData, $user->id);

            // Handle image upload
            if (isset($data['image']) && is_file($data['image'])) {
                $updatedUser = $this->userRepository->findOneBy(['id' => $user->id]);
                $this->uploadImage($data['image'], $updatedUser, $updatedUser->name);
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
     * Update student profile
     */
    public function updateStudentProfile(array $data, User $user): bool
    {
        try {
            DB::beginTransaction();

            // Fields to update for student
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'country_code' => $data['country_code'],
                'gender' => $data['gender'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'address' => $data['address'] ?? null,
                'bio' => $data['bio'] ?? null,
                'student_id' => $data['student_id'] ?? null,
                'grade' => $data['grade'] ?? null,
                'class' => $data['class'] ?? null,
                'academic_year' => $data['academic_year'] ?? null,
            ];

            $this->userRepository->update($updateData, $user->id);

            // Handle image upload
            if (isset($data['image']) && is_file($data['image'])) {
                $updatedUser = $this->userRepository->findOneBy(['id' => $user->id]);
                $this->uploadImage($data['image'], $updatedUser, $updatedUser->name);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Student Profile Update Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update parent profile
     */
    public function updateParentProfile(array $data, User $user): bool
    {
        try {
            DB::beginTransaction();

            // Fields to update for parent
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'country_code' => $data['country_code'],
                'gender' => $data['gender'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'address' => $data['address'] ?? null,
                'bio' => $data['bio'] ?? null,
                'occupation' => $data['occupation'] ?? null,
                'relationship_to_student' => $data['relationship_to_student'] ?? null,
                'national_id' => $data['national_id'] ?? null,
                'emergency_contact' => $data['emergency_contact'] ?? null,
            ];

            $this->userRepository->update($updateData, $user->id);

            // Handle image upload
            if (isset($data['image']) && is_file($data['image'])) {
                $updatedUser = $this->userRepository->findOneBy(['id' => $user->id]);
                $this->uploadImage($data['image'], $updatedUser, $updatedUser->name);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Parent Profile Update Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update profile based on user type
     */
    public function updateProfile(array $data, User $user): bool
    {
        if ($user->isAdmin()) {
            return $this->updateAdminProfile($data, $user);
        } elseif ($user->isStudent()) {
            return $this->updateStudentProfile($data, $user);
        } elseif ($user->isParent()) {
            return $this->updateParentProfile($data, $user);
        }

        return false;
    }
}
