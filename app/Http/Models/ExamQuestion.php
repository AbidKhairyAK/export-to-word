<?php



namespace App\Http\Models;



use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;



/**

 * @property integer $question_id

 * @property integer $exam_id

 * @property string $question_type

 * @property string $question_text

 * @property float $score

 */

class ExamQuestion extends Model {

    const TYPE_SINGLE = 'single';

    const TYPE_MULTI = 'multi';

    const TYPE_MATCH = 'match';

    const TYPE_ESSAY = 'essay';

    /**

     * The table associated with the model.

     *

     * @var string

     */

    protected $table = '_exam_question';



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

    protected $primaryKey = 'question_id';



    /**

     * @var array

     */

    protected $fillable = [

        'exam_id',

        'question_type',

        'question_text',

        'score'

    ];



    public function exam_question_option(){

        return $this->hasMany('App\Http\Models\ExamQuestionOption', 'question_id', 'question_id');

    }

    public function exam_question_option_match(){

        return $this->hasMany('App\Http\Models\ExamQuestionOptionMatch', 'question_id', 'question_id');

    }

    public static function getScoreTrue($quesId='',$exId=''){
        $data = DB::table('_exam_answer_score')
                ->select('*')
                ->leftJoin('_exam_question_score','_exam_answer_score.score_id','=','_exam_question_score.score_id')
                ->where('_exam_answer_score.question_id','=',$quesId)
                ->where('_exam_question_score.exam_id','=',$exId)
                ->get();
        return $data;
    }


    public static function listType(){

        return [

            self::TYPE_SINGLE,

            self::TYPE_MULTI,

            self::TYPE_MATCH,

            self::TYPE_ESSAY

        ];

    }



    public static function getScore($entryId, $type) {

        switch ($type) {

            case ExamQuestion::TYPE_SINGLE:

            case ExamQuestion::TYPE_MULTI:

                return DB::table('_exam_answer')

                    ->select(DB::raw("COALESCE(SUM(_exam_answer_score.score),0) AS score"))

                    ->join('_exam_question', function ($query) use ($type) {

                        $query->on('_exam_question.question_id', '=', '_exam_answer.question_id')

                            ->where('_exam_question.question_type', '=', $type);

                    })

                    ->join('_exam_answer_score', function ($query) {

                        $query->on('_exam_answer_score.question_id', '=', '_exam_answer.question_id')

                            ->on('_exam_answer_score.option_id', '=', '_exam_answer.option_id');

                    })

                    ->where('entry_id', $entryId)

                    ->first()

                    ->score;

            case ExamQuestion::TYPE_MATCH:

                return DB::table('_exam_answer')

                    ->select(DB::raw("COALESCE(SUM(_exam_answer_score.score),0) AS score"))

                    ->join('_exam_question', function ($query) {

                        $query->on('_exam_question.question_id', '=', '_exam_answer.question_id')

                            ->where('_exam_question.question_type', '=', ExamQuestion::TYPE_MATCH);

                    })

                    ->join('_exam_answer_score', function ($query) {

                        $query->on('_exam_answer_score.question_id', '=', '_exam_answer.question_id')

                            ->on('_exam_answer_score.option_id', '=', '_exam_answer.option_id')

                            ->on('_exam_answer_score.option_match_id', '=', '_exam_answer.option_match_id');

                    })

                    ->where('entry_id', $entryId)

                    ->first()

                    ->score;

            case ExamQuestion::TYPE_ESSAY:

                return DB::table('_exam_answer')

                    ->select(DB::raw("COALESCE(SUM(_exam_answer.answer_score),0) AS score"))

                    ->join('_exam_question', function ($query) {

                        $query->on('_exam_question.question_id', '=', '_exam_answer.question_id')

                            ->where('_exam_question.question_type', '=', ExamQuestion::TYPE_ESSAY);

                    })

                    ->where('entry_id', $entryId)

                    ->first()

                    ->score;

            default:

                return 0;

        }

    }



    public static function getScoreMax($examId, $type) {

        if ($type != ExamQuestion::TYPE_ESSAY) {

            return DB::table('_exam_answer_score')

                ->select(DB::raw("COALESCE(SUM(_exam_answer_score.score),0) AS score"))

                ->join('_exam_question', function ($query) use ($examId, $type) {

                    $query->on('_exam_question.question_id', '=', '_exam_answer_score.question_id')

                        ->where('_exam_question.exam_id', '=', $examId)

                        ->where('_exam_question.question_type', '=', $type);

                })

                ->first()

                ->score;

        }

        return DB::table('_exam_question')

            ->select(DB::raw("COALESCE(SUM(_exam_question.score),0) AS score"))

            ->where('_exam_question.exam_id', '=', $examId)

            ->where('_exam_question.question_type', '=', ExamQuestion::TYPE_ESSAY)

            ->first()

            ->score;

    }

}

