<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Thn_ajar extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_thn_ajar';
    protected $primaryKey = 'thn_ajar_id';

    protected $fillable = [
        'thn_ajar_id',
        'thn_ajar_value',
        'thn_ajar_status',
        'thn_ajar_start',
        'thn_ajar_end',
        'thn_ajar_semester'
    ];

}
