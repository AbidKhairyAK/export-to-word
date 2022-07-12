<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $answer_id
 * @property integer $entry_id
 * @property integer $option_id
 * @property integer $question_id
 * @property float $answer_score
 * @property string $answer_date
 * @property string $answer_correction_text
 * @property string $answer_text
 * @property integer $option_match_id
 */
class ExamAnswer extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_exam_answer';

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
    protected $primaryKey = 'answer_id';

    /**
     * @var array
     */
    protected $fillable = [
        'entry_id',
        'question_id',
        'option_id',
        'answer_score',
        'answer_date',
        'answer_correction_text',
        'answer_text',
        'option_match_id',
    ];

}
