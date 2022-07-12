<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class IuranTipe extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_iuran_tipe';
    protected $primaryKey = 'tipe_id';

    protected $fillable = [
        'tipe_id',
        'tipe_nama',
        'tipe_desc',
        'created_date',
        'created_by',
        'modified_date',
        'modified_by'
    ];
}