<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;

class Vc extends Model
{

    public $timestamps = false;
    protected $table = '_vc';
    protected $primaryKey = 'vc_id';

}