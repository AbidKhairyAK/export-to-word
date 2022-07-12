<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Dm extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_dm';
    protected $primaryKey = 'dm_id';

    protected $fillable = [
        'dm_id',
        'dm_group_name',
        'dm_status',
        'dm_created_at'
    ];
    
    public function dmconversation() {
        return $this->hasMany('App\Http\Models\DmConversation', 'dm_id','dm_id');
    }

    public function dmparticipant() {
        return $this->hasMany('App\Http\Models\DmParticipant', 'dm_id','dm_id');
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
