<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class SppProduct extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_spp_product';
    protected $primaryKey = 'sp_id';

    protected $fillable = [
        'sp_thn_ajar',
        'sp_nama',
        'sp_desc',
        'sp_harga',
        'sp_denda',
        'sp_tingkat',
        'sp_type',
        'sp_payment',
        'sp_tahun',
        'sp_semester',
        'sp_max_date',
        'sp_create_date'
        
    ];

    public function SppProductCicilan() {
        return $this->hasMany('App\Http\Models\SppProductCicilan', 'sp_id', 'sp_id');
    }

}
