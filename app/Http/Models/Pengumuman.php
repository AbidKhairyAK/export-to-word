<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Pengumuman extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_content';
    protected $primaryKey = 'content_id';

    protected $fillable = [
        'content_id',
        'content_type',
        'cat_id',
        'content_name',
        'content_image',
        'content_alias',
        'content_shortdesc',
        'content_desc',
        'content_tags',
        'content_hits',
        'content_status',
        'content_create_date',
        'content_publish_date'
    ];
}
