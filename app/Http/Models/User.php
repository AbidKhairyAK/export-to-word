<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

//use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_user';
    protected $primaryKey = 'user_id';


    protected $fillable = [
        'user_id',
        'user_nama',
        'user_username',
        'user_password',
        'user_regid',
        'user_level',
        'user_status',
        'user_create_date',
        'user_foto'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'user_password',
    ];

    public function siswa() {
        return $this->hasOne('App\Http\Models\Siswa','user_id', 'user_id');
    }

    public function wali() {
        return $this->belongsTo('App\Http\Models\Wali');
    }

    public function guru() {
        return $this->belongsTo('App\Http\Models\Guru','user_id', 'user_id');
    }

//    public function getJWTIdentifier()
//    {
//        return $this->getKey();
//    }
//
//    public function getJWTCustomClaims()
//    {
//        return [];
//    }
//
//    public function getAuthPassword() {
//        return $this->user_password;
//    }
}
