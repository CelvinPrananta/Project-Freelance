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
        'user_id',
        'email',
        'username',
        'employee_id',
        'join_date',
        'status',
        'role_name',
        'avatar',
        'tgl_lahir',
        'email_verified_at',
        'password',
        'tema_aplikasi',
        'status_online'
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
        'password' => 'hashed',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, "user_team", "user_id", "team_id")
            ->withPivot("status");
    }

    public function teamRelations()
    {
        return $this->hasMany(UserTeam::class);
    }

    public function cardHistories()
    {
        return $this->hasMany(CardHistory::class);
    }

    public function isOnline()
    {
        return $this->status_online === 'Online';
    }
}
