<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class DmConversation extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_dm_conversation';
    protected $primaryKey = 'conversation_id';

    protected $fillable = [
        'conversation_id',
        'dm_id',
        'customer_id',
        'conversation_type',
        'conversation_text',
        'file_name',
        'conversation_data_id',
        'conversation_date'
    ];
    
    public function user() {
        return $this->hasOne('App\Http\Models\User', 'user_id', 'customer_id');
    }

    public function dm() {
        return $this->hasOne('App\Http\Models\Dm', 'dm_id', 'dm_id');
    }

/*
    public function participant() {
        return $this->hasOne('App\Http\Models\Participant', 'participant_id');
    }


    public function questionoption() {
        return $this->hasOne('App\Http\Models\Questionoption', 'option_id');
    }

    public function soal() {
        return $this->hasOne('App\Http\Models\Soal', 'question_id');
    }*/

}
