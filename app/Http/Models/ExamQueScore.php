<?php



namespace App\Http\Models;



use Illuminate\Database\Eloquent\Model;


class ExamQueScore extends Model {

    protected $table = '_exam_question_score';


    public $timestamps = false;


    protected $primaryKey = 'exam_question_score_id';


    protected $fillable = [

        'exam_id',

        'score_id',

        'score'

    ];


    public function exam() {

        return $this->hasOne('App\Http\Models\Exam', 'exam_id', 'exam_id');

    }



    public function examanswerscore(){

        return $this->hasOne('App\Http\Models\ExamAnswerScore', 'score_id', 'score_id');

    }



}

