<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class SettingMessages extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_setting_messages';
}
