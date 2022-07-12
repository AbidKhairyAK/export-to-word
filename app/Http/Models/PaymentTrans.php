<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class PaymentTrans extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_payment_trans';
    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'payment_id',
        'siswa_id',
        'payment_tgl_bayar',
        'payment_nominal',
        'channel_id',
        'payment_account',
        'payment_status',
        'payment_remark',
        'payment_misc',
        'settlement_status',
        'settlement_desc',
        'settlement_date',
        'settlement_by',
        'created_date',
        'created_by',
        'modified_date',
        'modified_by'
    ];
}