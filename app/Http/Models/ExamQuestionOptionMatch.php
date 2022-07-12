<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $option_match_id
 * @property integer $question_id
 * @property string $option_match_text
 */
class ExamQuestionOptionMatch extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_exam_question_option_match';

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
    protected $primaryKey = 'option_match_id';

    /**
     * @var array
     */
    protected $fillable = [
        'question_id',
        'option_match_text',
    ];

    public function exam_category() {
        return $this->belongsTo('App\Http\Models\ExamQuestion', 'question_id', 'question_id');
    }

}
