<?php



namespace App\Http\Models;



use Illuminate\Database\Eloquent\Model;



/**

 * @property integer $exam_cat_id

 * @property string $exam_cat_name

 */

class ExamCategory extends Model {

    /**

     * The table associated with the model.

     *

     * @var string

     */

    protected $table = '_exam_category';



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

    protected $primaryKey = 'exam_cat_id';



    /**

     * @var array

     */

    protected $fillable = [

        'exam_cat_type',
        'exam_cat_name'

    ];



    public function exam(){

        return $this->hasMany('App\Http\Models\Exam', 'exam_cat_id', 'exam_cat_id');

    }



}

