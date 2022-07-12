<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class IuranMaster extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_iuran_master';
    protected $primaryKey = 'master_id';

    protected $fillable = [
        'master_id',
        'group_id',
        'tipe_id',
        'master_nominal',
        'master_tgl_jatuh_tempo',
        'created_date',
        'created_by',
        'modified_date',
        'modified_by'
    ];
}