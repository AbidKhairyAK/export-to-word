<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Siswa extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_siswa';
    protected $primaryKey = 'siswa_id';

    protected $fillable = [
        'siswa_id',
        'user_id',
        'wali_id',
        'kelas_id',
        'siswa_nisn',
        'siswa_nis',
        'siswa_rfid',
        'siswa_tahun',
        'siswa_provinsi',
        'siswa_kota',
        'siswa_alamat',
        'siswa_nohp',
        'siswa_gender',
        'allow_exam',
        'siswa_tempat_lahir',
        'siswa_tanggal_lahir',
        'siswa_create_date'
    ];

    public function wali() {
        return $this->hasOne('App\Http\Models\Wali', 'wali_id', 'wali_id');
    }

    public function kelas() {
        return $this->hasOne('App\Http\Models\Kelas', 'kelas_id', 'kelas_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Http\Models\User', 'user_id', 'user_id');
    }

    /*public function wali() {
        return $this->belongsTo('App\Http\Models\Wali', 'wali_id', 'wali_id');
    }*/

}