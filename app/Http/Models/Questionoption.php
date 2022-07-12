<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Questionoption extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_exam_question_option';
    protected $primaryKey = 'option_id';

    protected $fillable = [
        'option_id',
        'question_id',
        'option_text',
        'is_true_answer'
    ];

    public function soal() {
        return $this->hasOne('App\Http\Models\Soal', 'question_id');
    }

}
