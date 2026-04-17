<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword;

    protected $guard = "admin";

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'email_verified_at', // Include any other necessary fields
        'remember_token',
        'created_at',
        'updated_at',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $table = 'admins';

    public function sendPasswordResetNotification($token)
    {
        ResetPasswordNotification::createUrlUsing(function ($notifiable, $token) {
            return url(route('admin.password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
        });

        $this->notify(new ResetPasswordNotification($token));
    }

    public function activities()
    {
        return $this->hasMany(UserActivity::class, 'user_id');
    }
    // ...
public function departments()
{
    return $this->belongsToMany(\App\Models\Department::class, 'admin_department')->withTimestamps();
}


// (Optional helper)
public function hasDepartment(string $name): bool
{
    return $this->departments->contains('name', $name);
}

}
