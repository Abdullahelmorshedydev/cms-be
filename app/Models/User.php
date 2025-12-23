<?php

namespace App\Models;

use App\Traits\HasMedia;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens, HasTranslations, MustVerifyEmailTrait, HasMedia;

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
        'phone',
        'country_code',
        'reset_code',
        'reset_code_expires_at',
        'address',
        'bio',
        'job_title'
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
            'is_admin' => 'boolean',
            'address' => 'array',
        ];
    }

    public function imagePath(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->image ? $this->image->url : asset('dashboard/assets/img/avatars/1.png'),
        );
    }

    // Helper methods to check user type
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function getRolesDisplayNames()
    {
        $formattedDisplayNames = '';
        foreach ($this->roles as $role) {
            $formattedDisplayNames .= '- ' . json_decode($role->display_name)->{LaravelLocalization::getCurrentLocale()} . "<br>";
        }
        return $formattedDisplayNames;
    }
}
