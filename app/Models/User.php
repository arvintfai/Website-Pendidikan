<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasRoles, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
        ];
    }


    /**
     * For check is the user has role administrator.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return Auth::user()->can('develop') && Auth::user()->hasRole('administrator');
    }

    /**
     * For check is the user has role teacher.
     *
     * @return bool
     */
    public function isTeacher(): bool
    {
        return Auth::user()->hasRole('teacher');
    }

    /**
     * For check is the user has role student.
     *
     * @return bool
     */
    public function isStudent(): bool
    {
        return Auth::user()->hasRole('student');
    }

    /**
     *Get Avatar Url Attribut
     *
     * @return [type]
     */
    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : null;
    }

    /**
     * Function to give access permission on AdminPanel
     *
     * @param Panel $panel
     *
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // return str_ends_with($this->email, '@app.com');

        return $this->isAdmin() || $this->isTeacher() || $this->isStudent();
    }

    /**
     * Check permission to access admin panel
     *
     * @return [type]
     */
    public function userHasAccess()
    {
        return Auth::user()->hasRole(['administrator', 'student', 'teacher']);
    }
}
