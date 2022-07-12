<?php

namespace App\Http\Models;

use App\Http\Classes\Helper;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Support\Facades\DB;

class Soal extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_exam_question';
    protected $primaryKey = 'question_id';

    protected $fillable = [
        'question_id',
        'mapel_id',
        'materi_id',
        'question_type',
        'question_text',
        'question_pict',
        'question_type_pict',
        'keterangan',
        'create_by',
        'modified_by'
    ];

    public function mapel() {
        return $this->hasOne('App\Http\Models\Mapel', 'mapel_id', 'mapel_id');
    }
    public function user() {
        return $this->hasOne('App\Http\Models\User', 'user_id', 'user_id');
    }

    public function materi() {
        return $this->hasOne('App\Http\Models\Materi', 'materi_id');
    }

    public function questionoption() {
        return $this->hasMany('App\Http\Models\Questionoption', 'question_id');
    }

    public function questionoptionmatch() {
        return $this->hasMany('App\Http\Models\ExamQuestionOptionMatch', 'question_id');
    }

    public static function getOption($question_id){
        $data = DB::table('_exam_answer_score')
                ->select(
                    '_exam_answer_score.question_id','_exam_question_option.*','_exam_question_option_match.*'
                )
                ->leftjoin('_exam_question_option_match','_exam_answer_score.option_match_id','=','_exam_question_option_match.option_match_id')
                ->rightjoin('_exam_question_option','_exam_answer_score.option_id','=','_exam_question_option.option_id')
                ->where('_exam_answer_score.question_id', $question_id)
                ->get();
        return $data;
    }


    public static function getScore($question_id, $exam_id){
        $data = DB::table('_exam_question_score')
                ->join('_exam_answer_score','_exam_answer_score.score_id','=','_exam_question_score.score_id')
                ->where('_exam_question_score.exam_id',$exam_id)
                ->where('_exam_answer_score.question_id',$question_id)
                ->sum('_exam_question_score.score');
        return $data;
    }

    public static function getOptionWithScore($question_id, $match = ''){
        if ($match != '') {
            $data = DB::table('_exam_answer_score')
                ->select(
                    '_exam_answer_score.question_id','_exam_question_option.*','_exam_question_option_match.*'
                )
                ->leftjoin('_exam_question_option_match','_exam_answer_score.option_match_id','=','_exam_question_option_match.option_match_id')
                ->rightjoin('_exam_question_option','_exam_answer_score.option_id','=','_exam_question_option.option_id')
                ->where('_exam_answer_score.question_id', $question_id)
                ->get();


        }else{
            $data = DB::table('_exam_question')
                    ->select(
                        '_exam_question.question_id','_exam_question_option.*','_exam_question_option_match.*'
                    )
                    ->leftjoin('_exam_question_option_match','_exam_question.question_id','=','_exam_question_option_match.question_id')
                    ->rightjoin('_exam_question_option','_exam_question.question_id','=','_exam_question_option.question_id')
                    ->where('_exam_question.question_id', $question_id)
                    ->get();
        }

        return $data;
    }

    public static function getSoalUjian($exam_id, $question_type ,$page = 0){

        $question = DB::table('_exam_has_question')
            ->select(
                '_exam_question.*',
                '_exam_has_question.*'
            )
            ->leftjoin('_exam_question', '_exam_question.question_id', '=' , '_exam_has_question.question_id')
            ->when($question_type, function ($q, $question_type){
                $q->where('_exam_question.question_type', $question_type);
            })
            ->where('_exam_has_question.exam_id', $exam_id)
            ->orderBy('ehq_order','ASC')
            ->get();

        if(count($question) > 0){
            foreach ($question as $item) {
                if ($item->question_type !== 'essay') {
                    if ($item->question_type !== 'match') {
                        $item->opsi = DB::table('_exam_question')
                            ->select(
                                '_exam_question.question_id','_exam_question_option.*','_exam_question_option_match.*'
                            )
                            ->leftjoin('_exam_question_option_match','_exam_question.question_id','=','_exam_question_option_match.question_id')
                            ->rightjoin('_exam_question_option','_exam_question.question_id','=','_exam_question_option.question_id')
                            ->where('_exam_question.question_id', $item->question_id)
                            ->get();
                        if(count($item->opsi)>0){
                            foreach ($item->opsi as $itemOp) {
                                $itemOp->score = DB::table('_exam_question_score')
                                    ->select(
                                        '_exam_answer_score.*','_exam_question_score.*'
                                    )
                                    ->leftjoin('_exam_answer_score','_exam_answer_score.score_id','=','_exam_question_score.score_id')
                                    ->rightjoin('_exam','_exam.exam_id','=','_exam_question_score.exam_id')
                                    ->where('_exam_answer_score.option_id','=',$itemOp->option_id)
                                    ->where('_exam_answer_score.option_match_id','=',$itemOp->option_match_id)
                                    ->where('_exam_answer_score.question_id', $item->question_id)
                                    ->where('_exam.exam_id', $exam_id)
                                    ->get();
                            }

                        }
                    }else{
                        $item->opsi = DB::table('_exam_answer_score')
                            ->select(
                                '_exam_answer_score.question_id','_exam_question_option.*','_exam_question_option_match.*'
                            )
                            ->leftjoin('_exam_question_option_match','_exam_answer_score.option_match_id','=','_exam_question_option_match.option_match_id')
                            ->rightjoin('_exam_question_option','_exam_answer_score.option_id','=','_exam_question_option.option_id')
                            ->where('_exam_answer_score.question_id', $item->question_id)
                            ->get();

                        if(count($item->opsi)>0){
                            foreach ($item->opsi as $itemOp) {
                                $itemOp->score = DB::table('_exam_question_score')
                                    ->select(
                                        '_exam_answer_score.*','_exam_question_score.*'
                                    )
                                    ->leftjoin('_exam_answer_score','_exam_answer_score.score_id','=','_exam_question_score.score_id')
                                    ->leftjoin('_exam','_exam.exam_id','=','_exam_question_score.exam_id')
                                    ->where('_exam_answer_score.question_id', $item->question_id)
                                    ->where('_exam_answer_score.option_id','=',$itemOp->option_id)
                                    ->where('_exam.exam_id', $exam_id)
                                    ->groupBy('_exam_answer_score.option_id')
                                    ->groupBy('_exam_answer_score.option_match_id')
                                    ->get();
                            }

                        }
                    }
                }
            }
        }


        return $question;
    }

    public static function getSoalUjianOld($exam_id, $question_type ,$page = 0){
        $questionType = array();
        $questionType = DB::table('_exam_has_question')
                        ->select(
                            '_exam_question.question_type'
                        )
                        ->leftjoin('_exam_question', '_exam_question.question_id', '=' , '_exam_has_question.question_id')
                        ->where('_exam_has_question.exam_id', $exam_id)
                        ->when($question_type, function($pguru) use ($question_type){
                            return $pguru->where('question_type', '=', $question_type);
                        })
                        ->groupBy('question_type')
                        ->orderBy('question_type', 'ASC')
                        ->get();
        $questionType = json_decode(json_encode($questionType), true);
        $countQuestionType = count($questionType);

        if ($countQuestionType > 0) {
            for ($o=0; $o < $countQuestionType ; $o++) {
                $questionType[$o]['question'] = DB::table('_exam_has_question')
                        ->select(
                            '_exam_question.*'
                        )
                        ->leftjoin('_exam_question', '_exam_question.question_id', '=' , '_exam_has_question.question_id')
                        ->where('_exam_question.question_type', $questionType[$o]['question_type'])
                        ->where('_exam_has_question.exam_id', $exam_id)
                        ->get();


                $questionType[$o]['question'] = json_decode(json_encode($questionType[$o]['question']), true);
                //dd($questionType[$o]['question']);
                $jm = count($questionType[$o]['question']);

                for ($i=0; $i < $jm ; $i++) {
                   if ($questionType[$o]['question'][$i]['question_type'] !== 'essay') {
                        if ($questionType[$o]['question'][$i]['question_type'] !== 'match') {
                            $questionType[$o]['question'][$i]['opsi'] = DB::table('_exam_question')
                                    ->select(
                                        '_exam_question.question_id','_exam_question_option.*','_exam_question_option_match.*'
                                    )
                                    ->leftjoin('_exam_question_option_match','_exam_question.question_id','=','_exam_question_option_match.question_id')
                                    ->rightjoin('_exam_question_option','_exam_question.question_id','=','_exam_question_option.question_id')
                                    ->where('_exam_question.question_id', $questionType[$o]['question'][$i]['question_id'])
                                    ->get();
                        }else{
                            $questionType[$o]['question'][$i]['opsi'] = DB::table('_exam_answer_score')
                                    ->select(
                                        '_exam_answer_score.question_id','_exam_question_option.*','_exam_question_option_match.*'
                                    )
                                    ->leftjoin('_exam_question_option_match','_exam_answer_score.option_match_id','=','_exam_question_option_match.option_match_id')
                                    ->rightjoin('_exam_question_option','_exam_answer_score.option_id','=','_exam_question_option.option_id')
                                    ->where('_exam_answer_score.question_id', $questionType[$o]['question'][$i]['question_id'])
                                    ->get();
                        }

                        $questionType[$o]['question'][$i]['opsi'] = json_decode(json_encode($questionType[$o]['question'][$i]['opsi']), true);
                        $jmOp = count($questionType[$o]['question'][$i]['opsi']);

                        for ($x=0; $x < $jmOp ; $x++) {
                            if ($questionType[$o]['question'][$i]['question_type'] != 'match') {

                                $questionType[$o]['question'][$i]['opsi'][$x]['score'] = DB::table('_exam_question_score')
                                                                    ->select(
                                                                        '_exam_answer_score.*','_exam_question_score.*'
                                                                    )
                                                                    ->leftjoin('_exam_answer_score','_exam_answer_score.score_id','=','_exam_question_score.score_id')
                                                                    ->rightjoin('_exam','_exam.exam_id','=','_exam_question_score.exam_id')
                                                                    ->where('_exam_answer_score.option_id','=',$questionType[$o]['question'][$i]['opsi'][$x]['option_id'])
                                                                    ->where('_exam_answer_score.option_match_id','=',$questionType[$o]['question'][$i]['opsi'][$x]['option_match_id'])
                                                                    ->where('_exam_answer_score.question_id', $questionType[$o]['question'][$i]['question_id'])
                                                                    ->where('_exam.exam_id', $exam_id)
                                                                    ->get();
                            }else{

                                $questionType[$o]['question'][$i]['opsi'][$x]['score'] = DB::table('_exam_question_score')
                                                                    ->select(
                                                                        '_exam_answer_score.*','_exam_question_score.*'
                                                                    )
                                                                    ->leftjoin('_exam_answer_score','_exam_answer_score.score_id','=','_exam_question_score.score_id')
                                                                    ->leftjoin('_exam','_exam.exam_id','=','_exam_question_score.exam_id')
                                                                    ->where('_exam_answer_score.question_id', $questionType[$o]['question'][$i]['question_id'])
                                                                    ->where('_exam_answer_score.option_id','=',$questionType[$o]['question'][$i]['opsi'][$x]['option_id'])
                                                                    ->where('_exam.exam_id', $exam_id)
                                                                    ->groupBy('_exam_answer_score.option_id')
                                                                    ->groupBy('_exam_answer_score.option_match_id')
                                                                    ->get();
                            }
                        }
                    }
                }

            }
        }
                /*$questionType[$o]['question_type']*/


        return $questionType;
    }

    public static function getFormatExport($data = null)
    {
        $html = '';
        $html .= '<div><table style="margin-right: calc(8%); width: 100%;border: 2px solid black;padding: 0px">
                    <tbody style="page-break-inside: avoid">
                        <tr>
                            <td width="100" style="border-bottom: 1px solid black;padding: 15px;">Judul</td>
                            <td width="10" style="border-bottom: 1px solid black;padding: 15px;">:</td>
                            <td style="border-bottom: 1px solid black;padding: 15px;">'.$data['ujian']['exam_title'].'</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid black;padding: 15px;">Guru</td>
                            <td width="10" style="border-bottom: 1px solid black;padding: 15px;">:</td>
                            <td style="border-bottom: 1px solid black;padding: 15px;">'.$data['ujian']['user_nama'].'</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid black;padding: 15px;">Tanggal Mulai</td>
                            <td width="10" style="border-bottom: 1px solid black;padding: 15px;">:</td>
                            <td style="border-bottom: 1px solid black;padding: 15px;">'.$data['ujian']['exam_start_date'].'</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid black;padding: 15px;">Tanggal Selesai</td>
                            <td width="10" style="border-bottom: 1px solid black;padding: 15px;">:</td>
                            <td style="border-bottom: 1px solid black;padding: 15px;">'.$data['ujian']['exam_end_date'].'</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid black;padding: 15px;">Durasi</td>
                            <td width="10" style="border-bottom: 1px solid black;padding: 15px;">:</td>
                            <td style="border-bottom: 1px solid black;padding: 15px;">'.$data['ujian']['exam_time_limit'].'</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid black;padding: 15px;">Soal</td>
                            <td width="10" style="border-bottom: 1px solid black;padding: 15px;">:</td>
                            <td style="border-bottom: 1px solid black;padding: 15px;">'.$data['jumSoal'].'</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid black;padding: 15px;">Deskripsi</td>
                            <td width="10" style="border-bottom: 1px solid black;padding: 15px;">:</td>
                            <td style="border-bottom: 1px solid black;padding: 15px;">'.$data['ujian']['exam_desc'].'</td>
                        </tr>
                    </tbody>
                </table>
                </div><p><br></p>';

        if(count($data['soal']) > 0){
                $si = 1;
            foreach ($data['soal'] as $type) {

                if ($type->question_type == 'single'){
                    if (!empty($type->question_pict)){
                        $link = Helper::saveBlob($type->question_pict, $type->question_type_pict);
                        }
                    $row = count($type->opsi)+1;
                        $index = "A";
                        $html .= '<table style="margin-right: calc(5%); width: 100%; border: 2px solid black;padding: 0px;page-break-inside: avoid;border-collapse: collapse;">
                                    <tbody style="page-break-inside: avoid">
                                        <tr>
                                            <td rowspan="'.$row.'" width="20" style="border: 1px solid black;padding: 5px;">'.$si++.'</td>
                                            <td colspan="5" style="border: 1px solid black;padding-left: 5px;">
                                            '.$type->question_text.'<br>';

                                        if (!empty($type->question_pict)){
                                                $html .= '<img src="'.$link.'" width="100px"/>';
                                            }

                                            $html .= '</td></tr>';
                                    foreach ($type->opsi as $opsi) {
                                        if (!empty($opsi->option_pict)){
                                            $link = Helper::saveBlob($opsi->option_pict, $opsi->type_pict);
                                            }
                                            $html.='<tr>
                                                        <td width="20" style="border: 1px solid black;padding-left: 5px;">'.$index.'</td>
                                                    <td colspan="4" style="border: 1px solid black;padding-left: 5px;">'.$opsi->option_text.'<br>';

                                        if (!empty($type->question_pict)){
                                                $html .= '<img src="'.$link.'" width="100px"/>';
                                            }

                                            $html .= '</td></tr>';
                                            $index++;
                                        }
                        $html .= '</tbody></table><p><hr></p>';
                }

                if ($type->question_type == 'essay'){

                    if (!empty($type->question_pict)){
                        $link = Helper::saveBlob($type->question_pict, $type->question_type_pict);
                        }


                        $html .= '<table style="margin-right: calc(5%); width: 100%; border: 2px solid black;padding: 0px;page-break-inside: avoid;border-collapse: collapse;">
                                    <tbody style="page-break-inside: avoid">
                                        <tr>
                                        <td rowspan="5" width="20" style="border: 1px solid black;padding-left: 5px;">'.$si++.'</td>
                                        <td rowspan="5" style="border: 1px solid black;padding-left: 5px;">'.$type->question_text.'<br>';

                                        if (!empty($type->question_pict)){
                                                $html .= '<img src="'.$link.'" width="100px"/>';
                                            }

                                            $html .= '</td></tr>';
                        $html .= '</tbody></table><p><hr></p>';
                }


            }
        }
        $html .= '</div>';
        return $html;
    }

}
