<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class IuranDiskon extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_iuran_diskon';
    protected $primaryKey = 'diskon_id';

    protected $fillable = [
        'diskon_id',
        'diskon_nama',
        'diskon_desc',
        'diskon_nominal',
        'created_date',
        'created_by',
        'modified_date',
        'modified_by'
    ];
}