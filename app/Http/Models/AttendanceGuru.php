<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Presensi
 * @package App\Http\Models
 * @property integer $presensi_id
 * @property integer $guru_id
 * @property string $presensi_foto
 * @property string $presensi_tipe
 * @property string $presensi_status
 * @property string $presensi_create_date
 *
 */
class AttendanceGuru extends Model{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = '_guru_presensi';
    protected $primaryKey = 'presensi_id';

    const STATUS_PRESENT = 'present';
    const STATUS_LATE = 'late';
    const STATUS_ABSENT = 'absent';
    const STATUS_NOT_TAKEN = 'not_taken';

    protected $fillable = [
        'guru_id',
        'presensi_foto',
        'presensi_tipe',
        'presensi_status',
        'presensi_create_date'
    ];

    public static function getDay($guru_id, $date, $status, $page){
        $limit = PAGINATION_LIMIT;
        $data = DB::table('_guru_presensi')
            ->select(
                '*',
                DB::raw('CONCAT(presensi_foto) AS presensi_foto'),
                DB::raw('CONCAT("Tap ", presensi_tipe) AS presensi_tipe'),
                DB::raw('DATE_FORMAT(presensi_create_date, "%d/%m/%Y") AS presensi_create_date'),
                DB::raw('DATE_FORMAT(presensi_create_date, "%H:%i") AS presensi_create_time')
            )
            ->where('guru_id', $guru_id)
            ->when($status, function ($query) use ($status){
                return $query->where('presensi_status', $status);
            })
            ->where(DB::raw('DATE_FORMAT(presensi_create_date,"%Y-%m-%d")'), '=',$date)
            /*->offset(($page-1) * $limit)
            ->limit($limit)*/
            ->paginate(20);
        return $data;
    }

    public static function getDayCount($guru_id, $date, $status){
        $data = DB::table('_guru_presensi')
            ->where('guru_id', $guru_id)
            ->when($status, function ($query) use ($status){
                return $query->where('presensi_status', $status);
            })
            ->where(DB::raw('DATE_FORMAT(presensi_create_date,"%Y-%m-%d")'), '=',$date)
            ->count();
        return $data;
    }

    public static function getMonth($guru_id, $date, $status, $page){
        // $limit = PAGINATION_LIMIT;
        $data = DB::table('_guru_presensi')
            ->join('_guru', '_guru_presensi.guru_id', '=', '_guru.guru_id')
            ->join('_user', '_guru.user_id', '=', '_user.user_id')
            ->select(
                '*',
                DB::raw('CONCAT(presensi_foto) AS presensi_foto'),
                DB::raw('CONCAT("Tap ", presensi_tipe) AS presensi_tipe'),
                DB::raw('DATE_FORMAT(presensi_create_date, "%d/%m/%Y") AS presensi_create_date'),
                DB::raw('DATE_FORMAT(presensi_create_date, "%H:%i") AS presensi_create_time')
            )
            ->where('_guru_presensi.guru_id', $guru_id)
            ->when($status, function ($query) use ($status){
                return $query->where('presensi_status', $status);
            })
            ->where(DB::raw('DATE_FORMAT(presensi_create_date,"%Y-%m")'), '=',$date)
            // ->offset(($page-1) * $limit)
            // ->limit($limit)
            ->paginate(20);
        return $data;
    }

    public static function getRange($guru_id, $start, $end, $status, $page){
        // $limit = PAGINATION_LIMIT;
        $data = DB::table('_guru_presensi')
            ->join('_guru', '_guru_presensi.guru_id', '=', '_guru.guru_id')
            ->join('_user', '_guru.user_id', '=', '_user.user_id')
            ->select(
                '_guru_presensi.presensi_id',
                '_guru_presensi.presensi_status',
                '_guru.guru_id',
                '_user.user_nama',
                DB::raw('CONCAT(presensi_foto) AS presensi_foto'),
                DB::raw('CONCAT("Tap ", presensi_tipe) AS presensi_tipe'),
                DB::raw('DATE_FORMAT(presensi_create_date, "%d/%m/%Y") AS presensi_create_date'),
                DB::raw('DATE_FORMAT(presensi_create_date, "%H:%i") AS presensi_create_time')
            )
            ->whereBetween('_guru_presensi.presensi_create_date', [$start, $end])
            ->when($guru_id !== '0', function ($query) use ($guru_id){
                return $query->where('_guru_presensi.guru_id', $guru_id);
            })
            ->when($status, function ($query) use ($status){
                return $query->where('presensi_status', $status);
            })
            ->orderby('_guru_presensi.presensi_create_date', 'ASC')
            ->orderby('_guru.guru_id')
            // ->offset(($page-1) * $limit)
            // ->limit($limit)
            ->paginate(20);
        return $data;
    }


    public static function getExport($guru_id, $start, $end, $status, $page){

        // $limit = PAGINATION_LIMIT;
        $data = DB::table('_guru_presensi')
            ->join('_guru', '_guru_presensi.guru_id', '=', '_guru.guru_id')
            ->join('_user', '_guru.user_id', '=', '_user.user_id')
            ->select(
                '_guru_presensi.presensi_id',
                '_guru_presensi.presensi_status',
                '_guru.guru_id',
                '_user.user_nama',
                DB::raw('CONCAT(presensi_foto) AS presensi_foto'),
                DB::raw('CONCAT("Tap ", presensi_tipe) AS presensi_tipe'),
                DB::raw('DATE_FORMAT(presensi_create_date, "%d/%m/%Y") AS presensi_create_date'),
                DB::raw('DATE_FORMAT(presensi_create_date, "%H:%i") AS presensi_create_time')
            )
            ->whereBetween('_guru_presensi.presensi_create_date', [$start, $end])
            ->when($guru_id !== '0', function ($query) use ($guru_id){
                return $query->where('_guru_presensi.guru_id', $guru_id);
            })
            ->when($status, function ($query) use ($status){
                return $query->where('presensi_status', $status);
            })
            ->orderby('_guru_presensi.presensi_create_date', 'ASC')
            ->orderby('_guru.guru_id')
            // ->offset(($page-1) * $limit)
            // ->limit($limit)
            ->get();
        return $data;

    }


    public static function getMonthCount($guru_id, $date, $status){
        $data = DB::table('_guru_presensi', '_user')
            ->when($status, function ($query) use ($status){
                return $query->where('presensi_status', $status);
            })
            ->where('_guru_presensi.guru_id', $guru_id)
            ->where(DB::raw('DATE_FORMAT(presensi_create_date,"%Y-%m")'), '=',$date)
            ->count();
        return $data;
    }

    public static function detail($presensiId){
        $data = DB::table('_guru_presensi')
        ->join('_guru', '_guru_presensi.guru_id', '=', '_guru.guru_id')
        ->join('_user', '_guru.user_id', '=', '_user.user_id')
        ->select(
            '_guru_presensi.presensi_id',
            '_guru_presensi.presensi_status',
            '_guru.guru_id',
            '_user.user_nama',
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
            '_guru_presensi.presensi_id AS notification_id',
            DB::raw('"AttendanceGuru" AS notification_type'),
            DB::raw('"AttendanceGuru" AS notification_title'),
            DB::raw('"" AS notification_desc'),
            '_guru_presensi.presensi_create_date AS notification_date'
        ];
    }

    public static function selectFavorite(){
        return [
            '_guru_presensi.presensi_id AS data_id',
            DB::raw('CONCAT(presensi_foto) AS image'),
            '_favorite.fav_type AS type',
            '_user.user_nama AS desc',
            '_favorite.fav_create_date AS date',
        ];
    }

}
