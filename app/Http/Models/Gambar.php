<?php



namespace App\Http\Models;



use Illuminate\Auth\Authenticatable;

use Laravel\Lumen\Auth\Authorizable;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;



class Gambar extends Model implements AuthenticatableContract, AuthorizableContract

{

    use Authenticatable, Authorizable;



    /**

     * The attributes that are mass assignable.

     *

     * @var array

     */

    public $timestamps = false;

    protected $table = '_gambar';

    protected $primaryKey = 'id_question';



    protected $fillable = [

        'id_question',

        'nama_gambar',

        'gambar'

    ];

}

