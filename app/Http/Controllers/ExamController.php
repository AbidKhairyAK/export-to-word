<?php

namespace App\Http\Controllers;

use App\Http\Models\Exam;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord;

class ExamController extends Controller
{
    public function exportWord($examId)
    {
        // =========== get the required data ===========

        define('SCHOOL_CODE', 'devel');

        $client = new \GuzzleHttp\Client();
        $schoolRaw = $client->post('https://apisekolah.edumu.id/v1prod/sekolah', [
            'json' => ['code' => SCHOOL_CODE]
        ]);
        $schoolFull = json_decode($schoolRaw->getBody());
        $school = $schoolFull->data->sekolahs[0];

        $exam = Exam::getDetail($examId);

        $questions = DB::table('_exam_question')
            ->join('_exam_has_question', '_exam_question.question_id', '=', '_exam_has_question.question_id')
            ->select(DB::raw('
                _exam_has_question.*,
                _exam_question.question_id,
                _exam_question.question_type,
                _exam_question.question_text,
                _exam_question.question_pict,
                _exam_question.question_type_pict,
                _exam_question.keterangan
            '))
            ->where('_exam_has_question.exam_id', $examId)
            ->orderBy('question_type')
            ->orderBy('ehq_order')
            ->get();

        $options = DB::table('_exam_question')
            ->join('_exam_has_question', '_exam_question.question_id', '_exam_has_question.question_id')
            ->join('_exam_question_option', '_exam_question.question_id', '_exam_question_option.question_id')
            ->leftJoin('_exam_answer_score', '_exam_question_option.option_id', '_exam_answer_score.option_id')
            ->select(DB::raw('
                _exam_question.question_id,
                _exam_has_question.exam_id,
                NOT ISNULL(_exam_answer_score.score_id) AS is_correct,
                _exam_question_option.*
            '))
            ->where('exam_id', $examId)
            ->get();

        foreach ($questions as $key => $question) {
            $questions[$key]->options = $options->where('question_id', $question->question_id)->values();
        }

        $types = array_unique(
            array_map(function ($question) {
                return $question->question_type;
            }, $questions->toArray())
        );

        $questionsByType = [];

        foreach ($types as $type) {
            $questionsByType[] = json_decode(json_encode([
                'type' => $type,
                'questions' => $questions->where('question_type', $type)->values()
            ]));
        }


        // =========== set up phpword ===========

        $phpWord = new PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection();

        foreach ($types as $type) {
            $phpWord->addNumberingStyle(
                'multilevel-' . $type,
                array(
                    'type'   => 'multilevel',
                    'levels' => array(
                        array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
                        array('format' => 'upperLetter', 'text' => '%2.', 'left' => 900, 'hanging' => 360, 'tabPos' => 900),
                    ),
                )
            );
        }

        $typeTitles = [
            'single' => 'Pilihlah salah satu jawaban yang dianggap paling benar!',
            'multi' => 'Pilihlah satu atau beberapa jawaban yang dianggap paling benar!',
            'essay' => 'Jawablah pertanyaan berikut dengan benar!',
            'match' => 'Jodohkanlah soal dengan jawaban yang benar!',
        ];

        $romanNumbers = ['I', 'II', 'III', 'IV'];
        $alphabets = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];


        // =========== map the data into phpword ===========

        $section->addText($school->sekolah_nama, [
            'allCaps' => true,
            'size' => 20,
            'bold' => true
        ], [
            'lineHeight' => 1.45,
            'alignment' => PhpWord\SimpleType\Jc::CENTER
        ]);

        $section->addTextBreak(1);

        $section->addText($school->sekolah_alamat.', '.$school->sekolah_kota.', '.$school->sekolah_provinsi, null, [
            'alignment' => PhpWord\SimpleType\Jc::CENTER
        ]);

        $section->addImage('https://www.starpng.com/public/uploads/small/515754800446xdh2pkkilxprtidefgmx7z3ey94lcxor2achr5pn2tc3byqsd4ni04o4afvo8c8yavzswwhpmu21mxd1b4wadc45zsxicgiku6q.png', [
            'alignment' => PhpWord\SimpleType\Jc::CENTER,
            'height' => 50,
            'width' => 450,
        ]);

        $section->addTextBreak(1, null, ['lineHeight' => 1.15]);

        $section->addText($exam->exam_title, [
            'allCaps' => true,
            'size' => 16,
            'bold' => true
        ], [
            'lineHeight' => 1.5,
            'alignment' => PhpWord\SimpleType\Jc::CENTER
        ]);

        $section->addTextBreak(2, null, ['lineHeight' => 1.15]);

        $textRun = $section->addTextRun(['lineHeight' => 1.5]);
        $textRun->addText('MATA PELAJARAN: ');
        $textRun->addText($exam->mapel_nama, ['bold' => true]);

        $textRun = $section->addTextRun(['lineHeight' => 1.5]);
        $textRun->addText('ALOKASI WAKTU: ');
        $textRun->addText($exam->exam_time_limit, ['bold' => true]);

        $textRun = $section->addTextRun(['lineHeight' => 1.5]);
        $textRun->addText('JUMLAH SOAL: ');
        $textRun->addText(count($questions) . ' Soal', ['bold' => true]);

        $textRun = $section->addTextRun(['lineHeight' => 1.5]);
        $textRun->addText('WAKTU PELAKSANAAN: ');
        $textRun->addText($exam->exam_start_date, ['bold' => true]);

        $textRun = $section->addTextRun(['lineHeight' => 1.5]);
        $textRun->addText('KELAS / PROGRAM: ');

        $section->addTextBreak(2, null, ['lineHeight' => 1.15]);

        foreach ($questionsByType as $typeIndex => $questionItem) {

            $section->addText($romanNumbers[$typeIndex] . '. ' . $typeTitles[$questionItem->type], [
                'bold' => true
            ], [
                'lineHeight' => 1.5
            ]);

            $section->addTextBreak(1, null, ['lineHeight' => 1.15]);

            foreach ($questionItem->questions as $key => $question) {
                $section->addListItem(strip_tags($question->question_text), 0, null, 'multilevel-' . $questionItem->type, [
                    'lineHeight' => 1.15,
                    'keepNext' => count($question->options) > 0 || !empty($question->question_type_pict),
                    'keepLines' => true,
                ]);

                if ($question->question_type_pict) {
                    $textRun = $section->addTextRun([
                        'keepNext' => count($question->options) > 0,
                        'lineHeight' => 1.75,
                    ]);

                    $questionImageName = base_path($key . '-question-image.' . explode('/', $question->question_type_pict)[1]);
                    file_put_contents($questionImageName, base64_decode($question->question_pict));

                    $textRun->addImage(file_get_contents($questionImageName), [
                        'width' => 250,
                    ]);

                    unlink($questionImageName);
                }

                preg_match_all(
                    "/<a\s[^>]*href\s*=\s*([\"\']??)([^\"\' >]*?)\\1[^>]*>(.*)<\/a>/siU",
                    $question->question_text,
                    $questionImages,
                    PREG_SET_ORDER
                );

                if (!empty($questionImages)) {
                    foreach ($questionImages as $questionImage) {
                        $mediaUrl = substr($questionImage[2], 0, 2) === '//' ? 'https:' . $questionImage[2] : $questionImage[2];
                        $separatedUrl = explode('.', $mediaUrl);
                        $mediaExt = $separatedUrl[count($separatedUrl) - 1];
                        $acceptedMediaExt = collect(['jpg', 'jpeg', 'png', 'webp']);

                        if ($acceptedMediaExt->contains($mediaExt)) {
                            $textRun = $section->addTextRun([
                                'keepNext' => count($question->options) > 0,
                                'lineHeight' => 1.75,
                            ]);

                            $textRun->addImage($mediaUrl, [
                                'width' => 250,
                            ]);
                        }
                    }
                }

                foreach ($question->options as $optionKey => $option) {
                    $section->addListItem($option->option_text ? strip_tags($option->option_text) : '', 1, null, 'multilevel-' . $questionItem->type, [
                        'lineHeight' => 1.15,
                        'keepLines' => true,
                        'keepNext' => $optionKey !== count($question->options) - 1 || !empty($option->type_pict),
                    ]);

                    if ($option->type_pict) {
                        $textRun = $section->addTextRun([
                            'keepNext' => $optionKey !== count($question->options) - 1,
                            'lineHeight' => 1.75,
                        ]);

                        $optionImageName = base_path($key . '-option-image.' . explode('/', $option->type_pict)[1]);
                        file_put_contents($optionImageName, base64_decode($option->option_pict));

                        $textRun->addImage(file_get_contents($optionImageName), [
                            'width' => 250,
                        ]);

                        unlink($optionImageName);
                    }
                }

                $section->addTextBreak(1, null, ['lineHeight' => 1.15]);
            }

            $section->addTextBreak(1, null, ['lineHeight' => 1.15]);
        }

        $singleQuestions = $questions->where('question_type', 'single')->values();

        if (count($singleQuestions)) {
            $section->addPageBreak();

            $section->addText('Kunci Jawaban Pilihan Ganda Satu Jawaban:', [
                'bold' => true
            ], [
                'lineHeight' => 1.5
            ]);

            $section->addTextBreak(1, null, ['lineHeight' => 1.15]);

            foreach ($singleQuestions as $key => $question) {
                $isCorrectCollections = $question->options->where('is_correct', 1)->keys();
                $correctOption = count($isCorrectCollections) ? $alphabets[$isCorrectCollections[0]] : '';
                $section->addText(($key + 1) . '. ' . $correctOption);
            }
        }

        $filename = $exam->exam_title . ', ' . date('Y-m-d', strtotime($exam->exam_start_date)) . '.docx';
        $objWriter = PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filename);

        return response()->json([
            'filename' => $filename,
            'url' => url($filename)
        ]);
    }

    /* public function list()
    {
        $res = DB::table('_exam_question')
            ->join('_exam_has_question', '_exam_question.question_id', '=', '_exam_has_question.question_id')
            ->select(DB::raw('_exam_question.question_id, _exam_has_question.*, COUNT(_exam_question.question_id) as question_count'))
            ->groupBy('_exam_has_question.exam_id')
            ->orderBy('question_count', 'desc')
            ->get();
        return response()->json($res);
    } */
}
