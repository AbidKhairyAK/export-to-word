<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class SppTransactionDetail extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_spp_transaction_detail';
    protected $primaryKey = 'std_id';

    protected $fillable = [
        'st_id',
        'sp_id',
        'spc_id',
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
        'sp_bulan',
        'sp_max_date',
        'sp_create_date'
    ];

    public function SppTransaction() {
        return $this->hasOne('App\Http\Models\SppTransaction', 'st_id', 'st_id');
    }

}
