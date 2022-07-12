<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Guru extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_guru';
    protected $primaryKey = 'guru_id';

    protected $fillable = [
        'guru_id',
        'user_id',
        'guru_nip',
        'guru_rfid',
        'guru_create_date'
    ];

     public function user()
     {
         return $this->hasOne('App\Http\Models\User', 'user_id', 'user_id');
     }

     public function gurupresensi()
     {
         return $this->belongsTo('App\Http\Models\GuruPresensi', 'guru_id', 'guru_id');
     }


}
