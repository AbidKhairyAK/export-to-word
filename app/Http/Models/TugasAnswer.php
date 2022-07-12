<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TugasAnswer
 * @package App\Http\Models
 *
 * @property integer tugas_answer_id
 * @property integer tugas_id
 * @property integer siswa_id
 * @property integer wali_id
 * @property string ta_status
 * @property string ta_file_name
 * @property string ta_text
 * @property string ta_correction_file_name
 * @property string ta_correction_text
 * @property float ta_nilai
 * @property string ta_create_date
 * @property string ta_verify_date
 * @property string ta_correction_date
 */
class TugasAnswer extends Model{
    const STATUS_NOT_ANSWER = 'unanswer';
    const STATUS_ANSWER = 'answer';
    const STATUS_VERIFY = 'verify';
    const STATUS_CORRECTION = 'correction';

    public $timestamps = false;
    protected $table = '_tugas_answer';
    protected $primaryKey = 'tugas_answer_id';

    protected $fillable = [
        'tugas_id',
        'siswa_id',
        'wali_id',
        'ta_status',
        'ta_file_name',
        'ta_text',
        'ta_correction_file_name',
        'ta_correction_text',
        'ta_nilai',
        'ta_create_date',
        'ta_verify_date',
        'ta_correction_date',
        'ta_url'
    ];
}
