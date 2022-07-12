<?php

namespace App\Http\Models;

//use Illuminate\Support\Facades\DB;
//use Illuminate\Database\Eloquent\Model;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class PresensiGuruDetail extends Model implements AuthenticatableContract, AuthorizableContract{

    use Authenticatable, Authorizable;

    public $timestamps = false;
    protected $table = '_presensi_guru_detail';
    protected $primaryKey = 'pg_id';

    protected $fillable = [
        'pgd_id',
        'pg_id',
        'siswa_id',
        'pgd_status',
        'pgd_note',
        'pgd_create_date'
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

}
