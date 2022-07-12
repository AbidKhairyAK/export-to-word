<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class SppProductCicilan extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_spp_product_cicilan';
    protected $primaryKey = 'spc_id';

    protected $fillable = [
        'sp_id',
        'spc_harga',
        'spc_denda',
        'spc_max_date'
    ];

    public function SppProduct() {
        return $this->belongsTo('App\Http\Models\SppProduct', 'sp_id', 'sp_id');
    }

}
