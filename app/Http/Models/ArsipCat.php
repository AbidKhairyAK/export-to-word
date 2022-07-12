<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class ArsipCat extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_arsip_category';
    protected $primaryKey = 'arsip_cat_id';

    protected $fillable = [
        'arsip_cat_id',
        'arsip_cat_name',
        'arsip_cat_status'
    ];


}