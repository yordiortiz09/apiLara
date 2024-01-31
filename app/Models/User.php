<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'users';
    protected $roles = 'roles';

    public function validaciondelrol(...$role)
    {

        $user2 = Role::where('id', $this->rol_id)->first();
        return in_array($user2->nombre, ...$role);
    }

    public function roles(...$role)
    {
        if (is_array(...$role)) {
            foreach ($role as $roles) {
                if ($this->hasRole(...$roles)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole(...$role)) {
                return true;
            }
        }



        return false;
    }

    public function hasRole($role)
    {




        if ($role == $this->rol_id) {
            return true;
        }

        return false;
    }

    public function role()

    {
        return $this->hasMany(Role::class);
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'rol_id',
        'status',
        'verification_code',
        'verification_code_expires_at',
        
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
}
