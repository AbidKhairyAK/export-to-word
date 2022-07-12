<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Favorite
 * @package App\Http\Models
 * @property integer $fav_id
 * @property integer $data_id
 * @property integer $user_id
 * @property string $fav_type
 * @property string $fav_create_date
 */
class Favorite extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const TYPE_PENGUMUMAN = 'pengumuman';
    const TYPE_BERITA = 'berita';
    const TYPE_EVENT = 'event';
    const TYPE_ACADEMIC = 'academic';
    const TYPE_ABSEN = 'absen';
    const TYPE_TUGAS = 'tugas';
    const TYPE_TODO = 'todo';

    public $timestamps = false;
    protected $table = '_favorite';
    protected $primaryKey = 'fav_id';

    protected $fillable = [
        'data_id',
        'user_id',
        'fav_type',
        'fav_create_date'
    ];

}
