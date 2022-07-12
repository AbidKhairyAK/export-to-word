<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class SiswaKelas extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_siswa_kelas';
    protected $primaryKey = 'sk_id';

    protected $fillable = [
        'sk_id',
        'siswa_id',
        'kelas_id',
        'tahun_ajar',
        'sk_status',
        'sk_create_date'
    ];

}
