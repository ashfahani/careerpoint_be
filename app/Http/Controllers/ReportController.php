<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function reportByPeriod(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:initial_period'],
            'id_prodi' => ['nullable', 'exists:m_prodi,id'],
            'angkatan' => ['required', 'numeric'],
        ]);
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $start_date = $request->start_date;
            $end_date   = $request->end_date;
            $angkatan   = $request->angkatan;
            $getData = array();

            $getUser = DB::table('m_users AS u')
                    ->join('m_prodi AS p', 'u.id_prodi', '=', 'p.id')
                    ->select('u.nim_nik AS nim_nik', 'u.name AS name', 'p.prodi_name AS prodi_name', 'u.tahun_id AS angkatan')
                    ->where('u.tahun_id', 'LIKE', '%'.$angkatan.'%');
            if(!empty($request->id_prodi) || isset($request->id_prodi)){
                $getUser->where('u.id_prodi', $request->id_prodi);
            }
            $getUser = $getUser->get();
            // return response()->json($getUser); 
            // exit();
            
            foreach($getUser AS $user)
            {   
                $totalScore = 0;
                $panitia=0; $organisasi=0; $magang=0; $lomba=0; $seminar=0; $publikasi=0;
                $tbl_comm = DB::table('t_cp_committee AS cp')
                ->select(DB::raw('IFNULL(SUM(cp.score), 0) AS Kepanitiaan'), DB::raw('IFNULL(SUM(cp.score), 0) AS TotalScore'))
                ->where('cp.approve', 'A')->where('cp.na', 'N')->where('cp.initial_period', '>=', $start_date)->where('cp.final_period', '<=', $end_date)->where('cp.id_user', $user->nim_nik)->first();

                $tbl_comp = DB::table('t_cp_competition AS cp')
                ->select(DB::raw('IFNULL(SUM(cp.score), 0) AS Perlombaan'), DB::raw('IFNULL(SUM(cp.score), 0) AS TotalScore'))
                ->where('cp.approve', 'A')->where('cp.na', 'N')->where('cp.initial_period', '>=', $start_date)->where('cp.final_period', '<=', $end_date)->where('cp.id_user', $user->nim_nik)->first();

                $tbl_intern = DB::table('t_cp_internship AS cp')
                ->select(DB::raw('IFNULL(SUM(cp.score), 0) AS Magang'), DB::raw('IFNULL(SUM(cp.score), 0) AS TotalScore'))
                ->where('cp.approve', 'A')->where('cp.na', 'N')->where('cp.initial_period', '>=', $start_date)->where('cp.final_period', '<=', $end_date)->where('cp.id_user', $user->nim_nik)->first();

                $tbl_org = DB::table('t_cp_organization AS cp')
                ->select(DB::raw('IFNULL(SUM(cp.score), 0) AS Kepengurusan'), DB::raw('IFNULL(SUM(cp.score), 0) AS TotalScore'))
                ->where('cp.approve', 'A')->where('cp.na', 'N')->where('cp.initial_period', '>=', $start_date)->where('cp.final_period', '<=', $end_date)->where('cp.id_user', $user->nim_nik)->first();

                $tbl_seminar = DB::table('t_cp_seminar AS cp')
                ->select(DB::raw('IFNULL(SUM(cp.score), 0) AS Seminar'), DB::raw('IFNULL(SUM(cp.score), 0) AS TotalScore'))
                ->where('cp.approve', 'A')->where('cp.na', 'N')->where('cp.initial_period', '>=', $start_date)->where('cp.final_period', '<=', $end_date)->where('cp.id_user', $user->nim_nik)->first();

                $tbl_publication = DB::table('t_cp_publication AS cp')
                ->select(DB::raw('IFNULL(SUM(cp.score), 0) AS Publikasi'), DB::raw('IFNULL(SUM(cp.score), 0) AS TotalScore'))
                ->where('cp.approve', 'A')->where('cp.na', 'N')->where('cp.initial_period', '>=', $start_date)->where('cp.final_period', '<=', $end_date)->where('cp.id_user', $user->nim_nik)->first();

                // return $tbl_comp;
                // exit();
                $panitia = $panitia + $tbl_comm->Kepanitiaan; 
                $organisasi = $organisasi + $tbl_org->Kepengurusan; 
                $magang = $magang + $tbl_intern->Magang; 
                $lomba = $lomba + $tbl_comp->Perlombaan; 
                $seminar = $seminar + $tbl_seminar->Seminar; 
                $publikasi = $publikasi + $tbl_publication->Publikasi;
                $totalScore = ($panitia + $organisasi + $magang + $lomba + $seminar + $publikasi);

                $data = array(
                    'nim_nik'   => $user->nim_nik,
                    'nama_mhs'  => $user->name,
                    'prodi'     => $user->prodi_name,
                    'angkatan'  => $user->angkatan,
                    'kepanitiaan'   => $panitia,
                    'kepengurusan'  => $organisasi,
                    'magang'        => $magang,
                    'perlombaan'    => $lomba,
                    'seminar'       => $seminar,
                    'publikasi'     => $publikasi,
                    'total'         => $totalScore
                );

                $getData[] = $data;
            }

            return ResponseFormatter::success($getData, 'Get Report Success');  
        } catch (\Throwable  $e) {
            Log::debug('reportByPeriod');
            Log::debug($e);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Get Report Failed', 400);
        }
    }

    public function reportByMhsw(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nim_nik' => ['required', 'exists:m_users,nim_nik'],
        ]);
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $tbl_comm = DB::table('t_cp_committee AS cp')->join('m_level_committee AS l', 'cp.id_level', 'l.id')->join('m_role_committee AS r', 'cp.id_role', 'r.id')
            ->select('cp.activity_name', 'cp.period', 'l.name AS tingkat', 'r.name AS posisi', 'cp.score')
            ->where('cp.approve', 'A')->where('cp.na', 'N')->where('cp.id_user', $request->nim_nik)->orderBy('cp.initial_period', 'asc')->get();

            $tbl_comp = DB::table('t_cp_competition AS cp')->join('m_level_competition AS l', 'cp.id_level', 'l.id')->join('m_role_competition AS r', 'cp.id_role', 'r.id')
            ->select('cp.activity_name', 'cp.period', 'l.name AS tingkat', 'r.name AS posisi', 'cp.score')
            ->where('cp.approve', 'A')->where('cp.na', 'N')->where('cp.id_user', $request->nim_nik)->orderBy('cp.initial_period', 'asc')->get();

            $tbl_intern = DB::table('t_cp_internship AS cp')->join('m_level_internship AS l', 'cp.id_level', 'l.id')->join('m_role_internship AS r', 'cp.id_role', 'r.id')
            ->select('cp.activity_name', 'cp.period', 'l.name AS tingkat', 'r.name AS posisi', 'cp.score')
            ->where('cp.approve', 'A')->where('cp.na', 'N')->where('cp.id_user', $request->nim_nik)->orderBy('cp.initial_period', 'asc')->get();

            $tbl_org = DB::table('t_cp_organization AS cp')->join('m_level_organization AS l', 'cp.id_level', 'l.id')->join('m_role_organization AS r', 'cp.id_role', 'r.id')
            ->select('cp.activity_name', 'cp.period', 'l.name AS tingkat', 'r.name AS posisi', 'cp.score')
            ->where('cp.approve', 'A')->where('cp.na', 'N')->where('cp.id_user', $request->nim_nik)->orderBy('cp.initial_period', 'asc')->get();

            $tbl_seminar = DB::table('t_cp_seminar AS cp')->join('m_level_seminar AS l', 'cp.id_level', 'l.id')->join('m_role_seminar AS r', 'cp.id_role', 'r.id')
            ->select('cp.activity_name', 'cp.period', 'l.name AS tingkat', 'r.name AS posisi', 'cp.score')
            ->where('cp.approve', 'A')->where('cp.na', 'N')->where('cp.id_user', $request->nim_nik)->orderBy('cp.initial_period', 'asc')->get();

            $tbl_publication = DB::table('t_cp_publication AS cp')->join('m_level_publication AS l', 'cp.id_level', 'l.id')->join('m_role_publication AS r', 'cp.id_role', 'r.id')
            ->select('cp.activity_name', 'cp.period', 'l.name AS tingkat', 'r.name AS posisi', 'cp.score')
            ->where('cp.approve', 'A')->where('cp.na', 'N')->where('cp.id_user', $request->nim_nik)->orderBy('cp.initial_period', 'asc')->get();

            $getData = array(
                'committee'     => $tbl_comm,
                'organization'  => $tbl_org,
                'internship'    => $tbl_intern,
                'competition'   => $tbl_comp,
                'seminar'       => $tbl_seminar,
                'publication'   => $tbl_publication
            );

            return ResponseFormatter::success($getData, 'Get Report Success');  
        } catch (\Throwable  $e) {
            Log::debug('reportByPeriod');
            Log::debug($e);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Get Report Failed', 400);
        }
    }
}
