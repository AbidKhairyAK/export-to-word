<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Presensi
 * @package App\Http\Models
 * @property integer $presensi_id
 * @property integer $siswa_id
 * @property string $presensi_foto
 * @property string $presensi_tipe
 * @property string $presensi_status
 * @property string $presensi_create_date
 *
 */
class Attendance extends Model{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_presensi';
    protected $primaryKey = 'presensi_id';

    const STATUS_PRESENT = 'present';
    const STATUS_LATE = 'late';
    const STATUS_ABSENT = 'absent';
    const STATUS_NOT_TAKEN = 'not_taken';

    protected $fillable = [
        'siswa_id',
        'presensi_foto',
        'presensi_tipe',
        'presensi_status',
        'presensi_create_date'
    ];

    public static function getDay($siswa_id, $date, $status, $page){
        $limit = PAGINATION_LIMIT;
        $data = DB::table('_presensi')
            ->select(
                '*',
                DB::raw('CONCAT(presensi_foto) AS presensi_foto'),
                DB::raw('CONCAT("Tap ", presensi_tipe) AS presensi_tipe'),
                DB::raw('DATE_FORMAT(presensi_create_date, "%d/%m/%Y") AS presensi_create_date'),
                DB::raw('DATE_FORMAT(presensi_create_date, "%H:%i") AS presensi_create_time')
            )
            ->where('siswa_id', $siswa_id)
            ->when($status, function ($query) use ($status){
                return $query->where('presensi_status', $status);
            })
            ->where(DB::raw('DATE_FORMAT(presensi_create_date,"%Y-%m-%d")'), '=',$date)
            /*->offset(($page-1) * $limit)
            ->limit($limit)*/
            ->paginate(20);
        return $data;
    }

    public static function getDayCount($siswa_id, $date, $status){
        $data = DB::table('_presensi')
            ->where('siswa_id', $siswa_id)
            ->when($status, function ($query) use ($status){
                return $query->where('presensi_status', $status);
            })
            ->where(DB::raw('DATE_FORMAT(presensi_create_date,"%Y-%m-%d")'), '=',$date)
            ->count();
        return $data;
    }

    public static function getMonth($siswa_id, $date, $status, $page){
        // $limit = PAGINATION_LIMIT;
        $data = DB::table('_presensi')
            ->join('_siswa', '_presensi.siswa_id', '=', '_siswa.siswa_id')
            ->join('_user', '_siswa.user_id', '=', '_user.user_id')
            ->select(
                '*',
                DB::raw('CONCAT(presensi_foto) AS presensi_foto'),
                DB::raw('CONCAT("Tap ", presensi_tipe) AS presensi_tipe'),
                DB::raw('DATE_FORMAT(presensi_create_date, "%d/%m/%Y") AS presensi_create_date'),
                DB::raw('DATE_FORMAT(presensi_create_date, "%H:%i") AS presensi_create_time')
            )
            ->where('_presensi.siswa_id', $siswa_id)
            ->when($status, function ($query) use ($status){
                return $query->where('presensi_status', $status);
            })
            ->where(DB::raw('DATE_FORMAT(presensi_create_date,"%Y-%m")'), '=',$date)
            // ->offset(($page-1) * $limit)
            // ->limit($limit)
            ->paginate(20);
        return $data;
    }

    public static function getRange($siswa_id, $start, $end, $status, $page){
        // $limit = PAGINATION_LIMIT;
        $data = DB::table('_presensi')
            ->join('_siswa', '_presensi.siswa_id', '=', '_siswa.siswa_id')
            ->join('_user', '_siswa.user_id', '=', '_user.user_id')
            ->join('_kelas', '_siswa.kelas_id', '=', '_kelas.kelas_id')
            ->select(
                '_presensi.presensi_id',
                '_presensi.presensi_status',
                '_presensi.keterangan',
                '_presensi.status_izin',
                '_siswa.siswa_id',
                '_user.user_nama',
                '_kelas.kelas_nama',
                DB::raw('CONCAT(presensi_foto) AS presensi_foto'),
                DB::raw('CONCAT("Tap ", presensi_tipe) AS presensi_tipe'),
                DB::raw('DATE_FORMAT(presensi_create_date, "%d/%m/%Y") AS presensi_create_date'),
                DB::raw('DATE_FORMAT(presensi_create_date, "%H:%i") AS presensi_create_time')
            )
            ->whereBetween('_presensi.presensi_create_date', [$start, $end])
            ->where('_presensi.siswa_id', $siswa_id)
            ->when($status, function ($query) use ($status){
                return $query->where('presensi_status', $status);
            })
            ->orderby('_presensi.presensi_create_date', 'ASC')
            ->orderby('_siswa.siswa_id')
            // ->offset(($page-1) * $limit)
            // ->limit($limit)
            ->paginate(20);
        return $data;
    }

    public static function getExport($siswa_id, $start, $end, $status, $page){
        // $limit = PAGINATION_LIMIT;
        $data = DB::table('_presensi')
            ->join('_siswa', '_presensi.siswa_id', '=', '_siswa.siswa_id')
            ->join('_user', '_siswa.user_id', '=', '_user.user_id')
            ->join('_kelas', '_siswa.kelas_id', '=', '_kelas.kelas_id')
            ->select(
                '_presensi.presensi_id',
                '_presensi.presensi_status',
                '_presensi.keterangan',
                '_presensi.status_izin',
                '_siswa.siswa_id',
                '_user.user_nama',
                '_kelas.kelas_nama',
                DB::raw('CONCAT(presensi_foto) AS presensi_foto'),
                DB::raw('CONCAT("Tap ", presensi_tipe) AS presensi_tipe'),
                DB::raw('DATE_FORMAT(presensi_create_date, "%d/%m/%Y") AS presensi_create_date'),
                DB::raw('DATE_FORMAT(presensi_create_date, "%H:%i") AS presensi_create_time')
            )
            ->whereBetween('_presensi.presensi_create_date', [$start, $end])
            ->where('_presensi.siswa_id', $siswa_id)
            ->when($status, function ($query) use ($status){
                return $query->where('presensi_status', $status);
            })
            ->orderby('_siswa.siswa_id')
            // ->offset(($page-1) * $limit)
            // ->limit($limit)
            ->get();
        return $data;
    }

    public static function getMonthCount($siswa_id, $date, $status){
        $data = DB::table('_presensi', '_user')
            ->when($status, function ($query) use ($status){
                return $query->where('presensi_status', $status);
            })
            ->where('_presensi.siswa_id', $siswa_id)
            ->where(DB::raw('DATE_FORMAT(presensi_create_date,"%Y-%m")'), '=',$date)
            ->count();
        return $data;
    }

    public static function detail($presensiId){
        $data = DB::table('_presensi')
        ->join('_siswa', '_presensi.siswa_id', '=', '_siswa.siswa_id')
        ->join('_user', '_siswa.user_id', '=', '_user.user_id')
        ->join('_kelas', '_siswa.kelas_id', '=', '_kelas.kelas_id')
        ->select(
            '_presensi.presensi_id',
            '_presensi.presensi_status',
            '_siswa.siswa_id',
            '_user.user_nama',
            '_kelas.kelas_nama',
                DB::raw('CONCAT(presensi_foto) AS presensi_foto'),
                DB::raw('CONCAT("Tap ", presensi_tipe) AS presensi_tipe'),
                DB::raw('DATE_FORMAT(presensi_create_date, "%d/%m/%Y") AS presensi_create_date'),
                DB::raw('DATE_FORMAT(presensi_create_date, "%H:%i") AS presensi_create_time')
            )
            ->where('presensi_id', $presensiId)
            ->get();
        return $data;
    }

    public static function selectNotification(){
        return [
            '_presensi.presensi_id AS notification_id',
            DB::raw('"Attendance" AS notification_type'),
            DB::raw('"Attendance" AS notification_title'),
            DB::raw('"" AS notification_desc'),
            '_presensi.presensi_create_date AS notification_date'
        ];
    }

    public static function selectFavorite(){
        return [
            '_presensi.presensi_id AS data_id',
            DB::raw('CONCAT(presensi_foto) AS image'),
            '_favorite.fav_type AS type',
            '_kelas.kelas_nama AS title',
            '_user.user_nama AS desc',
            '_favorite.fav_create_date AS date',
        ];
    }

}
