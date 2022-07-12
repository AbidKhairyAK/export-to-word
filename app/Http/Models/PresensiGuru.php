<?php

namespace App\Http\Models;

//use Illuminate\Support\Facades\DB;
//use Illuminate\Database\Eloquent\Model;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class PresensiGuru extends Model implements AuthenticatableContract, AuthorizableContract{
//    const STATUS_MASUK = 'masuk';
//    const STATUS_IZIN = 'izin';
//    const STATUS_ALPHA = 'alpha';

    use Authenticatable, Authorizable;

    public $timestamps = false;
    protected $table = '_presensi_guru';
    protected $primaryKey = 'pg_id';

    protected $fillable = [
        'jadwal_id',
        'kelas_id',
        'mapel_id',
        'user_id',
        'jadwal_hari',
        'jadwal_mulai',
        'jadwal_selesai',
        'jadwal_semester',
        'jadwal_lokasi',
        'pg_status',
        'pg_note',
        'pg_create_date',
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

    public function guru()
    {
        return $this->belongsTo('App\Http\Models\Guru', 'guru_id', 'guru_id');
    }

    public function pgd()
    {
        return $this->belongsTo('App\Http\Models\PresensiGuruDetail', 'pg_id', 'pg_id');
    }

}
