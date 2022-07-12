<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Materi extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_materi';
    protected $primaryKey = 'materi_id';

    protected $fillable = [
        'materi_id',
        'kelas_id',
        'mapel_id',
        'user_id',
        'materi_tipe',
        'materi_judul',
        'materi_file',
        'materi_filesize',
        'materi_status',
        'materi_create_date'
    ];

    public function mapel() {
        return $this->hasOne('App\Http\Models\Mapel', 'mapel_id', 'mapel_id');
    }

    public function kelas() {
        return $this->hasOne('App\Http\Models\Kelas', 'kelas_id', 'kelas_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Http\Models\User', 'user_id', 'user_id');
    }

}
