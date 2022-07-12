<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class DmParticipant extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_dm_participant';
    protected $primaryKey = 'participant_id';

    protected $fillable = [
        'participant_id',
        'dm_id',
        'customer_id',
        'participant_status',
        'participant_join_date',
        'participant_leave_date'
    ];
    
    public function dm() {
        return $this->hasOne('App\Http\Models\Dm', 'dm_id', 'dm_id');
    }

    public function user() {
        return $this->hasOne('App\Http\Models\User', 'user_id', 'customer_id');
    }

}
