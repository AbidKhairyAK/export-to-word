<?php



namespace App\Http\Models;



use Illuminate\Database\Eloquent\Model;
use DB;
use App\Http\Models\ExamHasQue;
use App\Http\Models\ExamQueScore;
use App\Http\Models\ExamAnswerScore;
use App\Http\Models\ExamParticipant;

class Exam extends Model {

    protected $table = '_exam';

    public $timestamps = false;

    protected $primaryKey = 'exam_id';

    protected $fillable = [

        'exam_cat_id',

        'mapel_id',

        'user_id',

        'exam_type',

        'exam_title',

        'exam_desc',

        'exam_start_date',

        'exam_end_date',

        'exam_time_limit',

        'exam_status',

        'exam_random',

        'exam_create_date'

    ];

    public static function getAll($mapel = '0', $guru = '0', $start, $end, $page = ''){

        $data = DB::table('_exam')
            ->select(
                '_exam.*','_user.user_nama','_mapel.mapel_nama','_exam_category.*'
            )
            ->leftJoin('_exam_category','_exam.exam_cat_id','=','_exam_category.exam_cat_id')
            ->leftJoin('_mapel','_mapel.mapel_id','=','_exam.mapel_id')
            ->leftJoin('_user','_user.user_id','=','_exam.user_id')

            ->when($start !== '', function($query) use ($start){

                //return $query->where('_exam.exam_start_date', '>=' ,$start);
                return $query->where(DB::raw('DATE_FORMAT(_exam.exam_start_date,"%Y-%m-%d")'), '>=',$start);

            })
            ->when($end !== '', function($query) use ($end){

                //return $query->where('_exam.exam_end_date', '<=',$end);
                return $query->where(DB::raw('DATE_FORMAT(_exam.exam_start_date,"%Y-%m-%d")'), '<=',$end);

            })
            ->when($mapel, function($query) use ($mapel){

                return $query->where('_mapel.mapel_id', '=', $mapel);

            })
            ->when($guru, function($query) use ($guru){

                return $query->where('_user.user_id', '=', $guru);

            })
            ->orderby('_exam.exam_create_date', 'DESC')
            ->paginate(20);
        //->toSql();
        //dd($data);
        return $data;
    }

    public static function getDetail($exam_id=''){

        $data = DB::table('_exam')
            ->select(
                '_exam.*','_user.user_nama','_mapel.mapel_nama'
            )
            ->leftJoin('_exam_category','_exam.exam_cat_id','=','_exam_category.exam_cat_id')
            ->leftJoin('_mapel','_mapel.mapel_id','=','_exam.mapel_id')
            ->leftJoin('_user','_user.user_id','=','_exam.user_id')
            ->where('exam_id', $exam_id)
            ->orderby('_exam.exam_create_date', 'DESC')
            ->first();

        return $data;
    }

    public function exam_category() {

        return $this->belongsTo('App\Http\Models\ExamCategory', 'exam_cat_id', 'exam_cat_id');

    }

    public function examhasque() {

        return $this->belongsTo('App\Http\Models\ExamHasQue', 'exam_id', 'exam_id');

    }

    public function examquescore() {

        return $this->belongsTo('App\Http\Models\ExamQueScore', 'exam_id', 'exam_id');

    }


    public function exam_participant(){

        return $this->hasOne('App\Http\Models\ExamParticipant', 'exam_id', 'exam_id');

    }

//    public static function getNilai($exam, $participant){
//        $examId = $exam;
//        $participantId = $participant;
//
//        $exam = DB::table('_exam')->where('exam_id','=',$examId)->first();
//        $totQue = ExamHasQue::where('exam_id',$examId)->count();
//
//        if ($exam) {
//            $examParticipant = ExamParticipant::select(['_exam_participant.*'])
//                ->join('_kelas', '_kelas.kelas_id', '_exam_participant.kelas_id')
//                ->join('_exam', '_exam.exam_id', '_exam_participant.exam_id')
//                ->join('_mapel','_mapel.mapel_id','_exam.mapel_id')
//                ->join('_user','_user.user_id','_exam.user_id')
//                ->where('_exam_participant.exam_id', $examId)
//                ->where('_kelas.kelas_status', '1')
//                ->where('_exam_participant.participant_id', '=', $participantId)
//                ->first();
//
//
//            if (!empty($examParticipant)) {
//
//                $examParticipant['sis'] = SiswaKelas::select([
//                    '_siswa_kelas.*',
//                    '_siswa.siswa_id',
//                    '_siswa.siswa_nisn',
//                    '_user.user_nama',
//                    '_user.user_id',
//                    '_exam_entry.entry_status',
//                    '_exam_entry.user_id as user_part',
//                    '_exam_entry.entry_id',
//                    DB::raw('(select sum(_exam_question_score.score) as scoreMax FROM _exam_question_score WHERE exam_id = ' . $examId . ') as scoreMax')
//                ])
//                    ->join('_siswa', '_siswa.siswa_id', '=', '_siswa_kelas.siswa_id')
//                    ->join('_user', '_user.user_id', '=', '_siswa.user_id')
//                    ->leftJoin('_exam_entry', '_user.user_id', '=', '_exam_entry.user_id')
//                    ->where('_siswa_kelas.kelas_id', $examParticipant->kelas_id)
//                    ->where('_exam_entry.participant_id', $examParticipant->participant_id)
//                    ->where('sk_status', '1')->get();
//
//                $sisId = [];
//
//                foreach ($examParticipant['sis'] as $si) {
//                    $si['notMatch'] = ExamAnswer::select(['_exam_question.question_id', '_exam_question_score.score'])
//                        ->join('_exam_question', '_exam_question.question_id', '=', '_exam_answer.question_id')
//                        ->join('_exam_answer_score', '_exam_answer.question_id', '=', '_exam_answer_score.question_id')
//                        ->join('_exam_question_score', '_exam_answer_score.score_id', '=', '_exam_question_score.score_id')
//                        ->where('entry_id', $si->entry_id)
//                        ->where('exam_id', '=', $examId)
//                        ->where('question_type', '!=', 'essay')
//                        ->where('_exam_answer_score.option_id', '=', \DB::raw('_exam_answer.option_id'))
//                        ->groupBy('_exam_question.question_id')
//                        ->get();
//
//                    $scoreNotMatch = 0;
//                    $nmC = $si['notMatch']->count();
//
//                    if (isset($si['notMatch'])){
//                        foreach ($si['notMatch'] as $notMatch) {
//                            $scoreNotMatch += $notMatch->score;
//                        }
//                    }
//
//                    $si['notMatch']['scoreNotMatch'] = $scoreNotMatch;
//
//                    $si['essay'] = ExamAnswer::select([DB::raw('ifnull(sum(answer_score),0) as scoreEssay')])
//                        ->join('_exam_question', '_exam_answer.question_id', '=', '_exam_question.question_id')
//                        ->where('entry_id', '=', $si->entry_id)
//                        ->where('_exam_question.question_type', '=', 'essay')
//                        ->first();
//
//                    $si['match'] = ExamAnswer::select([DB::raw('ifnull(sum(_exam_question_score.score),0) as scoreMatch')])
//                        ->join('_exam_question', '_exam_question.question_id', '=', '_exam_answer.question_id')
//                        ->join('_exam_answer_score', '_exam_answer.question_id', '=', '_exam_answer_score.question_id')
//                        ->join('_exam_question_score', '_exam_answer_score.score_id', '=', '_exam_question_score.score_id')
//                        ->where('entry_id', $si->entry_id)
//                        ->where('exam_id', '=', $examId)
//                        ->where('question_type', '<>', 'essay')
//                        ->where('_exam_answer_score.option_id', '=', \DB::raw('_exam_answer.option_id'))
//                        ->where('_exam_answer_score.option_match_id', '=', \DB::raw('_exam_answer.option_match_id'))
//                        ->first();
//
//                    $si['nilai'] = $si['match']['scoreMatch'] + $si['essay']['scoreEssay'] + $si['notMatch']['scoreNotMatch'];
//                    $si['totSoal'] = $totQue;
//
//                    $si['totJawab'] = ExamAnswer::join('_exam_question', '_exam_question.question_id', '=', '_exam_answer.question_id')
//                        ->join('_exam_answer_score', '_exam_answer.question_id', '=', '_exam_answer_score.question_id')
//                        ->join('_exam_question_score', '_exam_answer_score.score_id', '=', '_exam_question_score.score_id')
//                        ->where('entry_id', $si->entry_id)
//                        ->where('exam_id', '=', $examId)
//                        ->groupBy('_exam_answer.question_id')
//                        ->get();
//
//                    $si['totJawab'] = count($si['totJawab']);
//                    $si['totBenarNotMatch'] = $nmC;
//
//                    $sisId[] = $si->siswa_id;
//                }
//
//                $examParticipant['sisNotE'] = SiswaKelas::select(['_siswa.siswa_id','_siswa.kelas_id','_user.user_nama','_user.user_id'])
//                    ->join('_siswa','_siswa.siswa_id','=','_siswa_kelas.siswa_id')
//                    ->join('_user','_siswa.user_id','=','_user.user_id')
//                    ->where('_siswa_kelas.kelas_id',$examParticipant->kelas_id)
//                    ->whereNotIn('_siswa_kelas.siswa_id',$sisId)
//                    ->where('sk_status','1')
//                    ->get();
//
//                $examParticipant->sis = $examParticipant->sis->sortByDesc('nilai')->values();
//
//            }
//        }
//
//        return $examParticipant;
//
//    }

    public static function getNilaiReport($examId, $participantId, $cari)
    {

        $dtExam = Exam::select([
                '_exam.*',
                '_mapel.mapel_nama',
                '_user.user_nama'
            ])
            ->join('_mapel','_mapel.mapel_id','=','_exam.mapel_id')
            ->join('_user','_user.user_id','=','_exam.user_id')
            ->where('_exam.exam_id','=',$examId)
            ->first();

        $dtPart = ExamParticipant::find($participantId);

        if (empty($dtPart)){
            $res['success'] = '0';
            $res['message'] = 'Kelas Belum dipilih';
            $res['data'] = $dtExam;
            return $res;
        }

        $dtKelas = Kelas::find($dtPart->kelas_id);

        $dtSis = SiswaKelas::select(['_user.user_id'])
            ->join('_siswa', '_siswa.siswa_id', '=', '_siswa_kelas.siswa_id')
            ->join('_user', '_user.user_id', '=', '_siswa.user_id')
            ->when($dtPart, function ($query) use ($dtPart) {
                return $query->where('_siswa_kelas.kelas_id', $dtPart->kelas_id);
            })
            ->when($cari, function ($query) use ($cari) {
                return $query->where('_user.user_nama', 'LIKE', '%'.$cari.'%');
            })
            ->where('sk_status', '1')->get();


        $dtSiswa = [];

        if (!empty($dtSis)){
            for ($i=0;$i<count($dtSis);$i++){
                $dtSiswa[$i] = $dtSis[$i]['user_id'];
            }
        }

        $dtNilai = ExamNilai::select([
                '_exam_nilai.en_id',
                '_exam_nilai.user_id',
                '_exam_nilai.en_tot_jawab',
                '_exam_nilai.en_tot_soal',
                '_exam_nilai.en_tot_nilai',
                '_exam_nilai.en_nilai',
                '_exam_nilai.en_desc',
                '_user.user_nama'
            ])
            ->join('_user','_user.user_id','=','_exam_nilai.user_id')
            ->where('_exam_nilai.exam_id','=',$examId)
            ->whereIn('_exam_nilai.user_id',$dtSiswa)
            ->get();


        if (count($dtNilai) > 0){
            foreach ($dtNilai as $nilai) {
                $nilai['cSalah'] = 0;
                $nilai['cBenar'] = 0;
                $nilai['cNot'] = 0;
                $nilai['totNEssay'] = 0;
                $nilai['totPg'] = 0;
                $nilai['totEs'] = 0;

                $nilai['detJwb'] = ExamNilaiDetail::select([
                    'end_id',
                    '_exam_nilai_detail.question_id as qi1',
                    'end_jawab_alias',
                    'end_benar_alias',
                    'end_jawab_koreksi',
                    '_exam_question.question_type',
                    \DB::raw('substr(question_text, 1, 500) as question_text')
                ])
                ->join('_exam_question','_exam_nilai_detail.question_id','=','_exam_question.question_id')
                ->where('en_id','=',$nilai->en_id)
                ->orderBy('end_id','ASC')
                ->get();

                $soalNonJawb = array();
                if (count($nilai['detJwb']) > 0){
                    foreach ($nilai['detJwb'] as $qJ) {
                        if($qJ->end_jawab_alias == $qJ->end_benar_alias){
                            $nilai['cBenar'] += 1;
                        }else{
                            $nilai['cSalah'] += 1;
                        }
                        if($qJ->question_type != 'essay'){
                            $nilai['totPg'] += 1;
                        }else{
                            $nilai['totEs'] += 1;
                        }
                        $soalNonJawb[] = $qJ->qi1;
                    }
                }


                $nilai['notJwb'] = ExamHasQue::select([
                    '_exam_question.question_id as qi1',
                    '_exam_question.question_type',
                    \DB::raw('substr(question_text, 1, 500) as question_text')
                    ])
                    ->join('_exam_question','_exam_has_question.question_id','=','_exam_question.question_id')
                    ->whereNotIn('_exam_has_question.question_id',$soalNonJawb)
                    ->where('exam_id','=',$examId)
                    ->get();

                if (count($nilai['notJwb']) > 0){
                    foreach ($nilai['notJwb'] as $qNJ) {
                        if($qNJ->question_type != 'essay'){
                            $nilai['totPg'] += 1;
                        }else{
                            $nilai['totEs'] += 1;
                        }
                    }
                }

                $nilai['cNot'] = count($nilai['notJwb']);

            }
        }
        $dtExam['kelas'] = $dtKelas;
        $dtExam['nilai'] = $dtNilai;

        return $dtExam;
    }

    public static function getNilai($exam, $participant){
        $examId = $exam;
        $participantId = $participant;

        $exam = DB::table('_exam')->where('exam_id','=',$examId)->first();
        $totQue = ExamHasQue::where('exam_id',$examId)->count();

        if ($exam) {
            $examParticipant = ExamParticipant::select(['_exam_participant.*'])
                ->join('_kelas', '_kelas.kelas_id', '_exam_participant.kelas_id')
                ->join('_exam', '_exam.exam_id', '_exam_participant.exam_id')
                ->where('_exam_participant.exam_id', $examId)
                ->where('_kelas.kelas_status', '1')
                ->where('_exam_participant.participant_id', '=', $participantId)
                ->first();

            if (!empty($examParticipant)) {

                $examParticipant['sis'] = SiswaKelas::select([
                    '_siswa_kelas.*',
                    '_siswa.siswa_id',
                    '_siswa.siswa_nisn',
                    '_user.user_nama',
                    '_user.user_id',
                    '_exam_entry.entry_status',
                    '_exam_entry.user_id as user_part',
                    '_exam_entry.entry_id',
                    DB::raw('(select sum(_exam_question_score.score) as scoreMax FROM _exam_question_score WHERE exam_id = ' . $examId . ') as scoreMax')
                ])
                    ->join('_siswa', '_siswa.siswa_id', '=', '_siswa_kelas.siswa_id')
                    ->join('_user', '_user.user_id', '=', '_siswa.user_id')
                    ->leftJoin('_exam_entry', '_user.user_id', '=', '_exam_entry.user_id')
                    ->where('_siswa_kelas.kelas_id', $examParticipant->kelas_id)
                    ->where('_exam_entry.participant_id', $examParticipant->participant_id)
                    ->where('sk_status', '1')->get();

                $sisId = [];

                foreach ($examParticipant['sis'] as $si) {
                    $sisJwbSing = ExamAnswer::select([
                        '_exam_question.question_id',
                        '_exam_question_score.score',
                        '_exam_answer_score.option_id as jwbBen',
                        \DB::raw('_exam_answer.option_id as jwbSis')
                    ])
                        ->join('_exam_question', '_exam_question.question_id', '=', '_exam_answer.question_id')
                        ->join('_exam_answer_score', '_exam_answer.question_id', '=', '_exam_answer_score.question_id')
                        ->join('_exam_question_score', '_exam_answer_score.score_id', '=', '_exam_question_score.score_id')
                        ->where('entry_id', $si->entry_id)
                        ->where('exam_id', '=', $examId)
                        ->where('question_type', '=', 'single')
//                        ->where('_exam_answer_score.option_id', '=', \DB::raw('_exam_answer.option_id'))
                        ->groupBy('_exam_question.question_id')
                        ->get();

                    $scoreNotMatch = 0;
                    $totBenar = 0;
                    $totSalah = 0;

                    $jwbId = [];
                    if (isset($sisJwbSing)){
                        foreach ($sisJwbSing as $notMatch) {
                            if ($notMatch->jwbBen == $notMatch->jwbSis){
                                $scoreNotMatch += $notMatch->score;
                                $notMatch['status'] = true;
                                $totBenar += 1;
                            }else{
                                $notMatch['status'] = false;
                                $totSalah += 1;
                            }
                            $jwbId[] = $notMatch->question_id;
                        }
                    }


//                    $scoreNotMatch = ($scoreNotMatch/$si->scoreMax)*100;

                    $notJwb = ExamHasQue::select(['_exam_has_question.question_id'])
                        ->join('_exam_question', '_exam_question.question_id', '=', '_exam_has_question.question_id')
                        ->where('exam_id','=',$examId)
                        ->where('question_type','=','single')
                        ->whereNotIn('_exam_has_question.question_id', $jwbId)
                        ->get();

                    $si['single'] = ['jwb'=>$sisJwbSing,'notJwb'=>$notJwb,'scoreNotMatch'=> $scoreNotMatch ];

                    $siJwbEssay = ExamAnswer::select([
                        '_exam_question.question_id',
                        '_exam_answer.answer_score',
                        '_exam_answer.answer_text',
                        '_exam_answer.answer_correction_text'
                    ])
                        ->join('_exam_question', '_exam_answer.question_id', '=', '_exam_question.question_id')
                        ->where('entry_id', '=', $si->entry_id)
                        ->where('_exam_question.question_type', '=', 'essay')
                        ->get();

                    $scoreEssay = 0;
                    $jwbId = [];
                    if (isset($siJwbEssay)){
                        foreach ($siJwbEssay as $essay) {
                            $scoreEssay += $essay->answer_score;
                            $jwbId[] = $essay->question_id;
                        }
                    }
                    $notJwb = ExamHasQue::select(['_exam_has_question.question_id'])
                        ->join('_exam_question', '_exam_question.question_id', '=', '_exam_has_question.question_id')
                        ->where('exam_id','=',$examId)
                        ->where('question_type','=','essay')
                        ->whereNotIn('_exam_has_question.question_id', $jwbId)
                        ->get();
                    $si['essay'] = ['jwb'=>$siJwbEssay,'notJwb'=>$notJwb,'scoreEssay'=> $scoreEssay ];

                    //match
//                    $sisJwbMatch = ExamAnswer::select([
//                            '_exam_question.question_id',
//                            '_exam_question_score.score',
//                            \DB::raw('_exam_answer.option_id'),
//                            \DB::raw('_exam_answer.option_match_id')
//                        ])
//                        ->join('_exam_question', '_exam_question.question_id', '=', '_exam_answer.question_id')
//                        ->join('_exam_answer_score', '_exam_answer.question_id', '=', '_exam_answer_score.question_id')
//                        ->join('_exam_question_score', '_exam_answer_score.score_id', '=', '_exam_question_score.score_id')
//                        ->where('entry_id', $si->entry_id)
//                        ->where('exam_id', '=', $examId)
//                        ->where('question_type', '=', 'match')
//                        ->where('_exam_answer_score.option_id', '=', \DB::raw('_exam_answer.option_id'))
//                        ->where('_exam_answer_score.option_match_id', '=', \DB::raw('_exam_answer.option_match_id'))
//                        ->get();
//
//                    $scoreMatch = 0;
//                    $nmC = $sisJwbMatch->count();
//                    $jwbId = [];
//                    if (isset($sisJwbMatch)){
//                        foreach ($sisJwbMatch as $match) {
//                            if ($match->jwbBen == $match->jwbSis){
//                                $scoreMatch += $match->score;
//                                $match['status'] = true;
//                            }else{
//                                $match['status'] = false;
//                            }
//                            $jwbId[] = $match->question_id;
//                        }
//                    }
//                    $notJwb = ExamHasQue::select(['_exam_has_question.question_id'])
//                        ->join('_exam_question', '_exam_question.question_id', '=', '_exam_has_question.question_id')
//                        ->where('exam_id','=',$examId)
//                        ->where('question_type','=','single')
//                        ->whereNotIn('_exam_has_question.question_id', $jwbId)
//                        ->get();
//
//                    $si['single'] = ['jwb'=>$sisJwbSing,'notJwb'=>$notJwb,'scoreNotMatch'=> $scoreNotMatch ];

                    $si['nilai'] = (($si['essay']['scoreEssay'] + $si['single']['scoreNotMatch'])/$si->scoreMax)*100;

                    $si['nilai'] = round($si['nilai']);
                    $si['totSoal'] = $totQue;

                    /*$si['totJawab'] = ExamAnswer::join('_exam_question', '_exam_question.question_id', '=', '_exam_answer.question_id')
                        ->join('_exam_answer_score', '_exam_answer.question_id', '=', '_exam_answer_score.question_id')
                        ->join('_exam_question_score', '_exam_answer_score.score_id', '=', '_exam_question_score.score_id')
                        ->where('entry_id', $si->entry_id)
                        ->where('exam_id', '=', $examId)
                        ->groupBy('_exam_answer.question_id')
                        ->get();*/

                    $si['totJawabSin'] = $totBenar + $totSalah;
                    $si['totJawabEss'] = count($siJwbEssay);
                    $si['totBenarSin'] = $totBenar;
                    $si['totSalahSin'] = $totSalah;

                    $sisId[] = $si->siswa_id;
                }

                $examParticipant['sisNotE'] = SiswaKelas::select(['_siswa.siswa_id','_siswa.kelas_id','_user.user_nama','_user.user_id'])
                    ->join('_siswa','_siswa.siswa_id','=','_siswa_kelas.siswa_id')
                    ->join('_user','_siswa.user_id','=','_user.user_id')
                    ->where('_siswa_kelas.kelas_id',$examParticipant->kelas_id)
                    ->whereNotIn('_siswa_kelas.siswa_id',$sisId)
                    ->where('sk_status','1')
                    ->get();

                $examParticipant->sis = $examParticipant->sis->sortByDesc('nilai')->values();

            }
        }

        return $examParticipant;

    }

    public static function getAnalisa($exam, $participant){
        $examId = $exam;
        $participantId = $participant;

        $exam = Exam::find($examId);
        $exaHasQue = ExamHasQue::select([
            '_exam_question.question_id',
            \DB::raw('substr(question_text, 1, 500) as question_text')
        ])
            ->join('_exam_question','_exam_question.question_id','=','_exam_has_question.question_id')
            ->where('exam_id',$examId)
            ->where('_exam_question.question_type','=','single')
            ->orderBy('exam_has_question_id','ASC')
            ->get();

        if (count($exaHasQue)>0){
            foreach ($exaHasQue as $item) {
                $opsi = ExamQuestionOption::select([
                    'option_id'
                ])
                    ->where('question_id','=',$item->question_id)
                    ->orderBy('option_id','ASC')
                    ->get();

                $ben = '';

                $index = "A";
                $arrN = array();
                $gru = array();
                foreach($opsi as $value)
                {
                    $arrN[$index] = $value['option_id'];
                    $gru[] = $index;
                    $index++;
                }

                $item['aliasN'] = $gru;

                foreach ($opsi as $ito) {
                    $cekBen = ExamAnswerScore::where('question_id','=',$item->question_id)->where('option_id','=',$ito->option_id)->count();
                    if ($cekBen > 0){
                        $item['isBenar'] = array_search($ito->option_id,$arrN);
                        $item['isBenarId'] = $ito->option_id;
                    }
                }

                $item['opsiC'] = $opsi->count();

                $examParticipant = ExamParticipant::select(['_kelas.kelas_id'])
                    ->join('_kelas', '_kelas.kelas_id', '_exam_participant.kelas_id')
                    ->join('_exam', '_exam.exam_id', '_exam_participant.exam_id')
                    ->where('_exam_participant.exam_id', $examId)
                    ->where('_kelas.kelas_status', '1')
                    ->where('_exam_participant.participant_id', '=', $participantId)
                    ->first();

                if (!empty($examParticipant)) {
                    $sis = ExamEntry::select(['user_id'])->where('participant_id','=',$participantId)->get();
                    $jwbBen = 0;
                    $userIdEn = [];
                    if(count($sis)>0){
                        foreach ($sis as $si) {
                            $userIdEn[] = $si->user_id;
                        }
                    }

                    $nilaiEn = ExamNilai::select([
                        'en_id'
                        ])
                        ->whereIn('user_id',$userIdEn)
                        ->where('exam_id','=',$examId)
                        ->where('en_tot_jawab','>','0')
                        ->get();
                    $enId = [];
                    if (count($nilaiEn) > 0){
                        foreach ($nilaiEn as $ne) {
                            $enId[] = $ne->en_id;
                        }
                    }

                    $getNilaiDet = ExamNilaiDetail::whereIn('en_id', $enId)
                        ->where('question_id','=',$item->question_id)
                        ->get();

                    $BrandCollection = collect($getNilaiDet);

                    $brandsWithCount = $BrandCollection->groupBy('end_jawab_alias')->map(function($values) {
                        return $values->count();
                    })->sort()->reverse()->toArray();

                    $item['totSis'] = count($enId);
                    $totJwb = 0;
                    foreach ($brandsWithCount as $bwc => $bw) {
                        $totJwb += $bw;
                    }

                    $item['nonJwb'] = $item['totSis']-$totJwb;

                    $item['gr'] = $brandsWithCount;
                }
            }
        }

        return $exaHasQue;

    }

    public static function getAnalisaBack($exam, $participant){
        $examId = $exam;
        $participantId = $participant;

        $exaHasQue = ExamHasQue::select([
                '_exam_question.question_id',
                \DB::raw('substr(question_text, 1, 200) as question_text')
            ])
            ->join('_exam_question','_exam_question.question_id','=','_exam_has_question.question_id')
            ->where('exam_id',$examId)
            ->where('_exam_question.question_type','=','single')
            ->orderBy('exam_has_question_id','ASC')
            ->get();

        if (count($exaHasQue)>0){
            foreach ($exaHasQue as $item) {
                $opsi = ExamQuestionOption::select([
                        'option_id'
                    ])
                    ->where('question_id','=',$item->question_id)
                    ->orderBy('option_id','ASC')
                    ->get();

                $index = "A";
                $arrN = array();
                $gru = array();
                foreach($opsi as $value)
                {
                    $arrN[$index] = $value['option_id'];
                    $gru[] = $index;
                    $index++;
                }

                $item['arrN'] = $arrN;

                foreach ($opsi as $ito) {
                    $cekBen = ExamAnswerScore::where('question_id','=',$item->question_id)->where('option_id','=',$ito->option_id)->count();
                    if ($cekBen > 0){
                        $item['isBenar'] = array_search($ito->option_id,$arrN);
                        $item['isBenarId'] = $ito->option_id;
                    }
                }

                $item['opsiC'] = $opsi->count();

                $part = ExamParticipant::select(['kelas_id'])
                    ->where('participant_id','=', $participantId)
                    ->where('exam_id','=',$examId)
                    ->first();

                $jumSisAll = SiswaKelas::select([
                        '_user.user_id'
                    ])
                    ->join('_siswa', '_siswa.siswa_id', '=', '_siswa_kelas.siswa_id')
                    ->join('_user', '_user.user_id', '=', '_siswa.user_id')
                    ->where('_siswa_kelas.kelas_id','=' ,$part->kelas_id)
                    ->where('sk_status', '1')
                    ->get();

                $item['jumSisAll'] = count($jumSisAll) > 0 ? count($jumSisAll) : 0;
                $item['opsi'] = [];
                $getNilai = [];

                if (count($jumSisAll)> 0){
                    $siswaId = [];
                    foreach ($jumSisAll as $sisAll) {
                        $siswaId[] = $sisAll->user_id;
                    }
                    $getOpsi = ExamNilai::select(['end_jawab','end_jawab_alias'])
                        ->join('_exam_nilai_detail','_exam_nilai_detail.en_id','=','_exam_nilai.en_id')
                        ->where('exam_id','=',$examId)
                        ->whereIn('user_id',$siswaId)
                        ->where('question_id','=',$item->question_id)
                        ->orderBy('end_jawab_alias','DESC')
                        ->get();

                        if(count($getOpsi) > 0){
                            $item['opsi'] = $getOpsi->groupBy('end_jawab_alias')->map(function($values) {
                            return $values->count();
                        })->reverse()->toArray();
                    }

                }

//                $examParticipant = ExamParticipant::select(['_kelas.kelas_id'])
//                    ->join('_kelas', '_kelas.kelas_id', '_exam_participant.kelas_id')
//                    ->join('_exam', '_exam.exam_id', '_exam_participant.exam_id')
//                    ->where('_exam_participant.exam_id', $examId)
//                    ->where('_kelas.kelas_status', '1')
//                    ->where('_exam_participant.participant_id', '=', $participantId)
//                    ->first();
//
//                if (!empty($examParticipant)) {
//                    $sis = SiswaKelas::select([
//                            '_user.user_nama',
//                            '_user.user_id'
//                        ])
//                        ->join('_siswa', '_siswa.siswa_id', '=', '_siswa_kelas.siswa_id')
//                        ->join('_user', '_user.user_id', '=', '_siswa.user_id')
//                        ->where('_siswa_kelas.kelas_id','=' ,$examParticipant->kelas_id)
//                        ->where('sk_status', '1')
//                        ->get();
//
//                    $jwbBen = 0;
//                    if(count($sis)>0){
//                        foreach ($sis as $si) {
//                            $isEnt = ExamEntry::where('_exam_entry.participant_id', '=', $participantId)->where('user_id','=',$si->user_id)->get();
//                            if(count($isEnt)>0){
//                                $si['isDo'] = '1';
//                                foreach ($isEnt as $ise) {
//                                    $jwb = ExamAnswer::select(['option_id'])->where('entry_id','=',$ise->entry_id)
//                                        ->where('question_id','=',$item->question_id)
//                                        ->first();
//                                    if (isset($jwb)){
//                                        $si['jwb'] = array_search($jwb->option_id,$arrN);
//                                        if ($si['jwb']==$item['isBenar']){
//                                            $jwbBen += 1;
//                                        }
//                                    }else{
//                                        $si['jwb'] = 'Not Answer';
//                                    }
//                                }
//                            }else{
//                                $si['isDo'] = '0';
//                                $si['jwb'] = 'Not Answer';
//                            }
//                        }
//                    }
//                    $item['ben'] = $jwbBen;
//                    $item['sal'] = (count($exaHasQue)-$jwbBen);
//                    $BrandCollection = collect($sis);
//
//                    $brandsWithCount = $BrandCollection->groupBy('jwb')->map(function($values) {
//                        return $values->count();
//                    })->sort()->reverse()->toArray();
//
//
//                    $item['gr'] = $brandsWithCount;
//                    $item['sis'] = $sis;
//                }
            }
        }

        return $exaHasQue;

    }

    public static function getNilaiAll($exam,$cari){
        $examId = $exam;

        $exam = DB::table('_exam')->where('exam_id','=',$examId)->first();
        $totQue = ExamHasQue::where('exam_id',$examId)->count();

        if ($exam) {
            $examParticipant = ExamParticipant::select(['_exam_participant.*'])
                ->join('_kelas', '_kelas.kelas_id', '_exam_participant.kelas_id')
                ->join('_exam', '_exam.exam_id', '_exam_participant.exam_id')
                ->where('_exam_participant.exam_id', $examId)
                ->where('_kelas.kelas_status', '1')
                ->get();

            $kelasId = [];
            foreach ($examParticipant as $item) {
                $kelasId[] = $item->kelas_id;
            }

            if (!empty($examParticipant)) {

                $sis = SiswaKelas::select([
                    '_siswa.siswa_nisn',
                    '_kelas.kelas_nama',
                    '_user.user_nama',
                    '_exam_entry.entry_status',
                    '_exam_entry.user_id as user_part',
                    '_exam_entry.entry_id',
                    DB::raw('(select sum(_exam_question_score.score) as scoreMax FROM _exam_question_score WHERE exam_id = ' . $examId . ') as scoreMax')
                ])
                    ->join('_siswa', '_siswa.siswa_id', '=', '_siswa_kelas.siswa_id')
                    ->join('_kelas', '_kelas.kelas_id', '=', '_siswa_kelas.kelas_id')
                    ->join('_user', '_user.user_id', '=', '_siswa.user_id')
                    ->leftJoin('_exam_entry', '_user.user_id', '=', '_exam_entry.user_id')
                    ->whereIn('_siswa_kelas.kelas_id', $kelasId)
                    ->where('sk_status', '1')->get();

                $sisId = [];

                foreach ($sis as $si) {
                    $sisJwbSing = ExamAnswer::select([
                        '_exam_question.question_id',
                        '_exam_question_score.score',
                        '_exam_answer_score.option_id as jwbBen',
                        \DB::raw('_exam_answer.option_id as jwbSis')
                    ])
                        ->join('_exam_question', '_exam_question.question_id', '=', '_exam_answer.question_id')
                        ->join('_exam_answer_score', '_exam_answer.question_id', '=', '_exam_answer_score.question_id')
                        ->join('_exam_question_score', '_exam_answer_score.score_id', '=', '_exam_question_score.score_id')
                        ->where('entry_id', $si->entry_id)
                        ->where('exam_id', '=', $examId)
                        ->where('question_type', '=', 'single')
                        /*->where('_exam_answer_score.option_id', '=', \DB::raw('_exam_answer.option_id'))*/
                        ->groupBy('_exam_answer.question_id')
                        ->get();

                    $scoreNotMatch = 0;

                    $jwbId = [];
                    if (isset($sisJwbSing)){
                        foreach ($sisJwbSing as $notMatch) {
                            if ($notMatch->jwbBen == $notMatch->jwbSis){
                                $scoreNotMatch += $notMatch->score;
                            }
                            $jwbId[] = $notMatch->question_id;
                        }
                    }

                    $si['single'] = ['scoreNotMatch'=> $scoreNotMatch ];

                    $siJwbEssay = ExamAnswer::select([
                        '_exam_question.question_id',
                        '_exam_answer.answer_score',
                        '_exam_answer.answer_text',
                        '_exam_answer.answer_correction_text'
                    ])
                        ->join('_exam_question', '_exam_answer.question_id', '=', '_exam_question.question_id')
                        ->where('entry_id', '=', $si->entry_id)
                        ->where('_exam_question.question_type', '=', 'essay')
                        ->groupBy('_exam_answer.question_id')
                        ->get();

                    $scoreEssay = 0;
                    $jwbId = [];
                    if (isset($siJwbEssay)){
                        foreach ($siJwbEssay as $essay) {
                            $scoreEssay += $essay->answer_score;
                            $jwbId[] = $essay->question_id;
                        }
                    }
                    $si['essay'] = ['scoreEssay'=> $scoreEssay ];

                    $si['nilai'] = $si['essay']['scoreEssay'] + $si['single']['scoreNotMatch'];
                    $si['totSoal'] = $totQue;

                    $sisId[] = $si->entry_id;
                }

                $sis = $sis->sortByDesc('nilai')->values();

            }
        }

        return $sis;

    }



}
