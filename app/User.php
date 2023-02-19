<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Contracts\Auditable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

class User extends Authenticatable implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use Notifiable, HasRoleAndPermission, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $connection  = 'sqlsrv';    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'email_verified_at' => 'date:Y-m-d',
    // ];

    public function basedTrucks(){
        return $this->belongsToMany(BasedTruck::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
