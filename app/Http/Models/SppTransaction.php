<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class SppTransaction extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_spp_transaction';
    protected $primaryKey = 'st_id';

    protected $fillable = [
        'siswa_id',
        'st_code',
        'st_total',
        'st_status',
        'st_note',
        'st_finish_date',
        'st_finish_note',
        'st_cancel_date',
        'st_cancel_note',
        'st_create_date'
    ];

    public function SppTransactionDetail() {
        return $this->hasMany('App\Http\Models\SppTransactionDetail', 'st_id', 'st_id');
    }

}
