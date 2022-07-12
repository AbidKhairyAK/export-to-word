<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ExamNilaiDetail extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_exam_nilai_detail';

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
    protected $primaryKey = 'end_id';

}
