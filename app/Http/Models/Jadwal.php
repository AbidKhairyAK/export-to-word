<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Jadwal extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_jadwal';
    protected $primaryKey = 'jadwal_id';

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
        'jadwal_status',
        'jadwal_create_date'
    ];

    public function mapel() {
        return $this->hasOne('App\Http\Models\Mapel', 'mapel_id', 'mapel_id');
    }

    public function kelas() {
        return $this->hasOne('App\Http\Models\Kelas', 'kelas_id', 'kelas_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Http\Models\User', 'user_id', 'user_id');
    }

}
