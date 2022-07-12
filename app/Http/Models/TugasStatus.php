<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TugasStatus
 * @package App\Http\Models
 *
 * @property integer ts_id
 * @property integer siswa_id
 * @property integer tugas_id
 * @property string ts_status
 * @property string ts_complete_date
 */
class TugasStatus extends Model{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_tugas_status';
    protected $primaryKey = 'ts_id';

    const STATUS_UNCOMPLETE = 'uncomplete';
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETE = 'complete';

    protected $fillable = [
        'siswa_id',
        'tugas_id',
        'ts_status',
        'ts_complete_date',
    ];
}
