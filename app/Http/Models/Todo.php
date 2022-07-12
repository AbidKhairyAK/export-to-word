<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Todo extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_todo';
    protected $primaryKey = 'todo_id';

    protected $fillable = [
        'todo_id',
        'siswa_id',
        'todo_tanggal',
        'todo_judul',
        'todo_desc',
        'todo_status',
        'todo_create_date'
    ];


    public function siswa() {
        return $this->hasOne('App\Http\Models\Siswa', 'siswa_id', 'siswa_id');
    }

}
