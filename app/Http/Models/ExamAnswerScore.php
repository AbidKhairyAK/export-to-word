<?php



namespace App\Http\Models;



use Illuminate\Database\Eloquent\Model;



/**

 * @property integer $score_id

 * @property integer $question_id

 * @property string $option_id

 * @property string $option_match_id

 * @property float $score

 */

class ExamAnswerScore extends Model {

    /**

     * The table associated with the model.

     *

     * @var string

     */

    protected $table = '_exam_answer_score';



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

    protected $primaryKey = 'score_id';



    /**

     * @var array

     */

    protected $fillable = [

        'question_id',

        'option_id',

        'option_match_id'

    ];



}

