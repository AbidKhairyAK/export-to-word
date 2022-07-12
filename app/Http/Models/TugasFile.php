<?php
/**
 * Created by PhpStorm.
 * User: Luthfi
 * Date: 25/09/2017
 * Time: 23.29
 */

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class TugasFile
 * @package App\Http\Models
 *
 * @property integer tugas_file_id
 * @property integer tugas_id
 * @property string tugas_file_nama
 * @property string tugas_file_create_date
 */
class TugasFile extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_tugas_file';
    protected $primaryKey = 'tugas_file_id';

    protected $fillable = [
        'tugas_id',
        'tugas_file_nama',
        'tugas_file_create_date'
    ];


    public function tugas(){
        return $this->hasOne('App\Http\Models\Tugas','tugas_id', 'tugas_id');
    }

}