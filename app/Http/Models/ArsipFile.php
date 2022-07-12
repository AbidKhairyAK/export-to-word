<?php
/**
 * Created by PhpStorm.
 * User: PT SMS
 * Date: 8/28/2017
 * Time: 3:18 PM
 */

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class ArsipFile extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_arsip_file';

    protected $fillable = [
        'id',
        'user_id',
        'arsip_cat_id',
        'file_cat',
        'file_name',
        'file_ext',
        'file_url'
    ];

}