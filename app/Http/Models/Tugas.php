<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tugas
 * @package App\Http\Models
 *
 * @property integer tugas_id
 * @property integer kelas_id
 * @property integer mapel_id
 * @property integer user_id
 * @property string tugas_judul
 * @property string tugas_desc
 * @property string tugas_status
 * @property string tugas_option
 * @property string tugas_create_date
 * @property string tugas_due_date
 */
class Tugas extends Model{
    const OPTION_ONLINE = 'online';
    const OPTION_OFFLINE = 'offline';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_tugas';
    protected $primaryKey = 'tugas_id';

    protected $fillable = [
        'kelas_id',
        'mapel_id',
        'user_id',
        'tugas_judul',
        'tugas_desc',
        'tugas_status',
        'tugas_option',
        'tugas_create_date',
        'tugas_due_date'
    ];

    public function user() {
        return $this->hasOne('App\Http\Models\User','user_id', 'user_id');
    }

    public function mapel() {
        return $this->hasOne('App\Http\Models\Mapel','mapel_id', 'mapel_id');
    }

    public function tugas_file(){
        return $this->hasMany('App\Http\Models\TugasFile', 'tugas_id', 'tugas_id');
    }

    public function kelas(){
        return $this->hasOne('App\Http\Models\Kelas','kelas_id', 'kelas_id');
    }

    public static function selectNotification(){
        return [
            '_tugas.tugas_id AS notification_id',
            DB::raw('"Assignment" AS notification_type'),
            '_tugas.tugas_judul AS notification_title',
            '_tugas.tugas_desc AS notification_desc',
            '_tugas.tugas_create_date AS notification_date'
        ];
    }
}
