<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ExamNilai extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '_exam_nilai';

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
    protected $primaryKey = 'en_id';

    /**
     * @var array
     */
    protected $fillable = [
        'en_id',
        'exam_id',
        'user_id',
        'en_tot_jawab',
        'en_tot_soal',
        'en_tot_nilai',
        'en_nilai',
        'en_desc',
        'en_created_at',
        'en_edited_nilai',
        'en_edited_at',
        'en_edited_by',
        'en_edited_desc'
    ];

    public function detail()
    {
        return $this->hasMany('App\Http\Models\ExamNilaiDetail','en_id','en_id');
    }

}
