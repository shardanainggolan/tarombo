<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'current_family_id',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the families managed by the user.
     */
    public function families()
    {
        return $this->hasMany(Family::class);
    }

    /**
     * Get the currently active family for the user.
     */
    public function currentFamily()
    {
        return $this->belongsTo(Family::class, 'current_family_id');
    }

    /**
     * Get the family member record associated with this user account.
     */
    public function familyMemberProfile()
    {
        return $this->hasOne(FamilyMember::class, 'user_account_id');
    }
}
