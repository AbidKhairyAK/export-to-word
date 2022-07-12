<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class DmConversationDeleted extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_dm_conversation_deleted';
    protected $primaryKey = 'dcd_id';

    protected $fillable = [
        'dcd_id',
        'conversation_id',
        'customer_id',
        'dcd_date'
    ];
    
    public function dmconversation() {
        return $this->hasOne('App\Http\Models\DmConversation', 'conversation_id', 'conversation_id');
    }

    public function user() {
        return $this->hasOne('App\Http\Models\User', 'user_id', 'customer_id');
    }

}
