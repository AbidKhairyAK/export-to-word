<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class SiswaFinance extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_siswa_finance';
    protected $primaryKey = 'finance_id';

    protected $fillable = [
        'finance_id',
        'iuran_id',
        'finance_tipe',
        'finance_sifat',
        'created_date',
        'created_by',
        'modified_date',
        'modified_by'
    ];
}