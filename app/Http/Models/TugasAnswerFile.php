<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TugasAnswerFile
 * @package App\Http\Models
 *
 * @property integer ta_file_id
 * @property integer tugas_answer_id
 * @property string ta_file_nama
 * @property string ta_file_ext
 * @property float answer_file_size
 * @property string ta_file_create_date
 */
class TugasAnswerFile extends Model{

    public $timestamps = false;
    protected $table = '_tugas_answer_file';
    protected $primaryKey = 'ta_file_id';

    protected $fillable = [
        'tugas_answer_id',
        'ta_file_nama',
        'ta_file_ext',
        'answer_file_size',
        'ta_file_create_date',
    ];
}
