<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Wali extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_wali';
    protected $primaryKey = 'wali_id';

    protected $fillable = [
        'wali_id',
        'user_id',
        'wali_provinsi',
        'wali_kota',
        'wali_alamat',
        'wali_nohp',
        'wali_gender',
        'wali_tempat_lahir',
        'wali_tanggal_lahir',
        'wali_create_date'
    ];


    public function siswa() {
        return $this->hasMany('App\Http\Models\Siswa', 'wali_id', 'wali_id');
    }

    /*public function siswa() {
        return $this->belongsTo('App\Http\Models\Siswa', 'wali_id', 'wali_id');
    }*/

    public function user()
    {
        return $this->hasOne(User::class, 'user_id','user_id');
    }

}
