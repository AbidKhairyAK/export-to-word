<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $entry_id
 * @property integer $participant_id
 * @property integer $user_id
 * @property string $entry_status
 * @property string $entry_start_date
 * @property string $entry_end_date
 */
class ExamEntry extends Model {
    const STATUS_PROCESS = 'process';
    const STATUS_DONE = 'done';
    const STATUS_CORRECTION = 'correction';
    const STATUS_FINISH = 'finish';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_exam_entry';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'entry_id';

    /**
     * @var array
     */
    protected $fillable = [
        'participant_id',
        'user_id',
        'entry_status',
        'entry_start_date',
        'entry_end_date',
    ];

}
