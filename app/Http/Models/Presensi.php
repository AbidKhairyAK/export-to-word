<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Presensi extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_presensi';
    protected $primaryKey = 'presensi_id';

    protected $fillable = [
        'presensi_id',
        'siswa_id',
        'presensi_foto',
        'presensi_tipe',
        'presensi_status',
        'presensi_create_date'
    ];


    public function siswa() {
        return $this->hasOne('App\Http\Models\Siswa', 'siswa_id', 'siswa_id');
    }

}
