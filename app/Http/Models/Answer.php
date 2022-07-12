<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Answer extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_exam_answer';
    protected $primaryKey = 'answer_id';

    protected $fillable = [
        'answer_id',
        'participant_id',
        'user_id',
        'option_id',
        'question_id',
    ];

    public function participant() {
        return $this->hasOne('App\Http\Models\Participant', 'participant_id');
    }

    public function user() {
        return $this->hasOne('App\Http\Models\User', 'user_id');
    }

    public function questionoption() {
        return $this->hasOne('App\Http\Models\Questionoption', 'option_id');
    }

    public function soal() {
        return $this->hasOne('App\Http\Models\Soal', 'question_id');
    }

}
