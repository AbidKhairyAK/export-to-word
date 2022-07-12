<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Kategori extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_category';
    protected $primaryKey = 'cat_id';

    protected $fillable = [
        'cat_id',
        'cat_name',
        'cat_alias',
        'cat_desc',
        'cat_image',
        'cat_parent',
        'cat_level',
        'cat_status',
        'cat_root',
        'cat_order'
    ];

    public function konten() {
        return $this->belongsTo('App\Http\Models\Konten','cat_id', 'cat_id');
    }
}
