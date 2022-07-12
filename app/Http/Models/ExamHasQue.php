<?php



namespace App\Http\Models;



use Illuminate\Database\Eloquent\Model;


class ExamHasQue extends Model {

    /**

     * The table associated with the model.

     *

     * @var string

     */

    protected $table = '_exam_has_question';



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

    protected $primaryKey = 'exam_has_question_id';



    /**

     * @var array

     */

    protected $fillable = [

        'exam_id',

        'question_id'

    ];



    public function exam() {

        return $this->hasOne('App\Http\Models\Exam', 'exam_id', 'exam_id');

    }

    public function question() {

        return $this->hasOne('App\Http\Models\Soal', 'question_id', 'question_id');

    }



}

