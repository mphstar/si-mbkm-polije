<?php

namespace App;

use App\Models\Lecturer;
use App\Models\Partner;
use App\Models\Students;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'level'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function partner()
    {
        return $this->hasOne(Partner::class, 'users_id', 'id');
    }

    public function student()
    {
        return $this->hasOne(Students::class, 'users_id', 'id');
    }

    public function lecturer()
    {
        return $this->hasOne(Lecturer::class, 'users_id', 'id');
    }
}
