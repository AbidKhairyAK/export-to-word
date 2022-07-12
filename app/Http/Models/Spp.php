<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Spp extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_spp';
    protected $primaryKey = 'spp_id';

    protected $fillable = [
        'thn_ajar_id',
        'siswa_id',
        'spp_month',
        'spp_year',
        'spp_paid_date',
        'spp_status',
        'spp_create_date'
        
    ];

    public function thn_ajar() {
        return $this->hasOne('App\Http\Models\Thn_ajar', 'thn_ajar_id', 'thn_ajar_id');
    }

    public function siswa() {
        return $this->hasOne('App\Http\Models\Siswa', 'siswa_id', 'siswa_id');
    }

}
