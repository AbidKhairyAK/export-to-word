<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamQuestionPicture extends Model
{
    protected $table = '_exam_question_pictures';

    public $timestamps = false;

    protected $fillable = [

        'question_id',
        'question_type',
        'pic_type',

    ];
}
