<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use App\Enums\MediaTypeEnum;
use App\Enums\GenderEnum;
use App\Enums\StatusEnum;
use App\Enums\UserTypeEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, HasTranslations, MustVerifyEmailTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_active',
        'user_type',
        'phone',
        'country_code',
        'reset_code',
        'reset_code_expires_at',
        'gender',
        'date_of_birth',
        'address',
        'bio',
        'job_title',
        'student_id',
        'grade',
        'class',
        'academic_year',
        'parent_id',
        'occupation',
        'relationship_to_student',
        'national_id',
        'emergency_contact',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => StatusEnum::class,
            'is_admin' => 'boolean',
            'user_type' => UserTypeEnum::class,
            'gender' => GenderEnum::class,
            'date_of_birth' => 'date',
            'address' => 'array',
        ];
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediaable')->where('type', MediaTypeEnum::IMAGE->value);
    }

    public function imagePath(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->image ? $this->image->url : asset('dashboard/assets/img/avatars/1.png'),
        );
    }

    // Parent relationship - A student belongs to a parent
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // Children relationship - A parent has many students
    public function children(): HasMany
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    // Helper methods to check user type
    public function isAdmin(): bool
    {
        return $this->is_admin || $this->user_type === UserTypeEnum::ADMIN || $this->hasRole('admin') || $this->hasRole('super-admin');
    }

    public function isStudent(): bool
    {
        return $this->user_type === UserTypeEnum::STUDENT;
    }

    public function isParent(): bool
    {
        return $this->user_type === UserTypeEnum::PARENT;
    }
}
