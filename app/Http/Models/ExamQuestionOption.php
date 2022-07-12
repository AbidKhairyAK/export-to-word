<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $option_id
 * @property integer $question_id
 * @property string $option_text
 */
class ExamQuestionOption extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_exam_question_option';

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
    protected $primaryKey = 'option_id';

    /**
     * @var array
     */
    protected $fillable = [
        'question_id',
        'option_text',
    ];

    public function exam_category() {
        return $this->belongsTo('App\Http\Models\ExamQuestion', 'question_id', 'question_id');
    }

    public function exam_answer_score() {
        return $this->belongsTo('App\Http\Models\ExamAnswerScore', 'option_id', 'option_id');
    }

}
