<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Kelas extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_kelas';
    protected $primaryKey = 'kelas_id';

    protected $fillable = [
        'kelas_id',
        'shift_id',
        'kelas_nama',
        'kelas_level',
        'kelas_status',
        'kelas_create_date'
    ];

    public function shift() {
        return $this->hasOne('App\Http\Models\Shift','id', 'shift_id');
    }

}
