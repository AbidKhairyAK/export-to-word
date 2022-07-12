<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $participant_id
 * @property integer $exam_id
 * @property integer $kelas_id
 */
class ExamParticipant extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_exam_participant';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'participant_id';

    /**
     * @var array
     */
    protected $fillable = [
        'exam_id',
        'kelas_id'
    ];

    public function exam(){
        return $this->belongsTo('App\Http\Models\Exam', 'exam_id', 'exam_id');
    }

}
