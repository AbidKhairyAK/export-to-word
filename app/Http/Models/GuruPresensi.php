<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class GuruPresensi extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_guru_presensi';
    protected $primaryKey = 'presensi_id';

    protected $fillable = [
        'presensi_id',
        'guru_id',
        'presensi_foto',
        'presensi_tipe',
        'presensi_status',
        'presensi_create_date'
    ];

    public function guru()
    {
       return $this->hasOne(Guru::class, 'guru_id','guru_id');
    }
    


}
