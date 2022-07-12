<?php
/**
 * Created by PhpStorm.
 * User: PT SMS
 * Date: 8/28/2017
 * Time: 3:18 PM
 */

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Event extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_event';
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'event_id',
        'event_type',
        'event_judul',
        'event_foto',
        'event_shortdesc',
        'event_desc',
        'event_mulai',
        'event_selesai',
        'event_status',
        'event_create_date'
    ];
}