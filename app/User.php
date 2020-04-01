<?php

namespace App;

use App\Entity\Company;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'company_id', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function isAdmin()
    {
        return $this->role == self::ROLE_ADMIN;
    }

    public function changeRole($role): void
    {
        if (!\in_array($role, self::rolesList(), true)) {
            throw new \InvalidArgumentException('Undefined role "' . $role . '"');
        }
        if ($this->role === $role) {
            throw new \DomainException('Role is already assigned.');
        }
        $this->update(['role' => $role]);
    }

    public function rolesList()
    {
        return [
            self::ROLE_USER,
            self::ROLE_ADMIN,
        ];
    }
}
