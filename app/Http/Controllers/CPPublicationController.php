<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\CPPublication;
use App\Models\Logs;
use App\Models\MLevelPublication;
use App\Models\MRolePublication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CPPublicationController extends Controller
{
    public function getCP(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'page' => ['required', 'numeric'],
                'limit' => ['required', 'numeric'],
                'status' => ['required', 'string'],
            ]
        );
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $data = CPPublication::baseQuery();
            if ($request->search){
                $data = CPPublication::searchFilter($data, $request->search);
            }
            $data = CPPublication::statusFilter($data, $request->status);
            if(auth()->user()->id_user_role !== '1' || auth()->user()->id_user_role !== '2'){       // Sesuaikan dengan id_role user
                $data = $data->where('cp.id_user', auth()->user()->nim_nik);
            }
            $data =  $data->orderBy('cp.created_at', 'desc');
            $limit = $request->limit;
            if ($request->limit == 0)
                $limit = $data->get()->count();
            $data =  $data->paginate($limit);

            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get CP Publication',
                'stat' => 'success'
            );
            Logs::create($logInfo);

            return ResponseFormatter::success($data, 'Get CP by User');
        } catch (\Throwable  $e) {
            Log::debug('getCP');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get CP Publication',
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Get CP Failed', 400);
        }
    }

    public function getCPDetails($id)
    {
        try {
            if (!isset($id)){
                return ResponseFormatter::error([
                    'message' => 'Error! Contact IT Dev',
                    'error' => 'Parameter is missing',
                ], 'Get CP Failed', 400);
            } 
            $res = CPPublication::baseQuery()
                ->where('cp.id', '=', $id)
                ->get();
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get Details CP Publication ID = '.$id,
                'stat' => 'success'
            );
            Logs::create($logInfo);
            return ResponseFormatter::success($res, 'Get CP Details');
        } catch (\Throwable  $e) {
            Log::debug('getCPDetails');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get Details CP Publication ID = '.$id,
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Get CP Detail Failed', 400);
        }
    }

    public function add(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'id_activity_type' => ['exists:m_activity_type,id'],
                // 'id_activity_category' => ['exists:m_activity_category,id'],
                'activity_name' => ['required', 'string', 'max:255'],
                'initial_period' => ['required', 'date_format:Y-m-d'],
                'final_period' => ['required', 'date_format:Y-m-d', 'after_or_equal:initial_period'],
                'id_level' => ['exists:m_level_publication,id'],
                'id_role' => ['exists:m_role_publication,id'],
                'file' => ['nullable', 'file', 'max:2563', 'mimes:jpg,png,pdf,doc,docx'],     
            ]
        );
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $l_score = MLevelPublication::getScore($request->id_level)->where('id_activity_category', '2')->first();
            $r_score = MRolePublication::getScore($request->id_role)->first();
            if(empty($l_score) || empty($r_score)){
                return ResponseFormatter::error([], 'Level or Role Score are empty!', 404);
            }
            $score = floatval($l_score->score) * floatval($r_score->score);
            $file = '';
            $file_name = '';
            $file_save = '';
            $file_type = '';
            if(!empty($request->file('file')) || $request->file('file') !== ''){
                $file = $request->file('file');
                $file_name = auth()->user()->nim_nik."_".$file->getClientOriginalName();
                $file_save = "publication/".$file_name;
                $file_type = $file->getClientOriginalExtension();
                $file->storeAs('files/publication', $file_name);
            }
            
            $data = array(
                'id_activity_type' => $request->id_activity_type,
                'id_activity_category' => '2',  // 1. Internal      2. External
                'id_user' => auth()->user()->nim_nik,
                'activity_name' => addslashes(trim($request->activity_name)),
                'initial_period' => date('Y-m-d', strtotime($request->initial_period)), 
                'final_period' => date('Y-m-d', strtotime($request->final_period)),
                'period' => date('Y-m-d', strtotime($request->initial_period)) . " s/d " . date('Y-m-d', strtotime($request->final_period)),
                'organizer_name' => $request->organizer_name,
                'organizer_location' => $request->organizer_location,
                'id_level' => $request->id_level,
                'id_role' => $request->id_role,
                'score' => floatval($score),
                'file' => $file_save,
                'file_type' => $file_type,
                'created_by' => auth()->user()->nim_nik,
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $insert = CPPublication::insertGetId($data);

            if ($insert) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Add CP Publication ID = '.$insert,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'CP Registered');
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Add CP Publication',
                    'stat' => 'error'
                );
                Logs::create($logInfo);
                return ResponseFormatter::error([], 'Registered Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('add');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Add CP Publication',
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Registered Failed', 400);
        }
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id_cp' => ['required', 'exists:t_cp_publication,id'],
            'id_activity_type' => ['exists:m_activity_type,id'],
            'activity_name' => ['required', 'string', 'max:255'],
            'initial_period' => ['required', 'date_format:Y-m-d'],
            'final_period' => ['required', 'date_format:Y-m-d', 'after_or_equal:initial_period'],
            'id_level' => ['exists:m_level_publication,id'],
            'id_role' => ['exists:m_role_publication,id'],
            'file' => ['nullable', 'file', 'max:2563', 'mimes:jpg,png,pdf,doc,docx'],             
        ]);

        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $l_score = MLevelPublication::getScore($request->id_level)->first();
            $r_score = MRolePublication::getScore($request->id_role)->first();
            if(empty($l_score) || empty($r_score)){
                return ResponseFormatter::error([], 'Level or Role Score are empty!', 404);
            }
            $score = floatval($l_score->score) * floatval($r_score->score);
            $file = '';
            $file_name = '';
            $file_save = '';
            $file_type = '';
            if(!empty($request->file('file')) || $request->file('file') !== ''){
                $file = $request->file('file');
                $file_name = auth()->user()->nim_nik."_".$file->getClientOriginalName();
                $file_save = "publication/".$file_name;
                $file_type = $file->getClientOriginalExtension();
                $file->storeAs('files/publication', $file_name);
            }
            
            $data = array(
                'id_activity_type' => $request->id_activity_type,
                'activity_name' => addslashes(trim($request->activity_name)),
                'initial_period' => date('Y-m-d', strtotime($request->initial_period)), 
                'final_period' => date('Y-m-d', strtotime($request->final_period)),
                'period' => date('Y-m-d', strtotime($request->initial_period)) . " s/d " . date('Y-m-d', strtotime($request->final_period)),
                'organizer_name' => $request->organizer_name,
                'organizer_location' => $request->organizer_location,
                'id_level' => $request->id_level,
                'id_role' => $request->id_role,
                'score' => floatval($score),
                'file' => $file_save,
                'file_type' => $file_type,
                'updated_by' => auth()->user()->nim_nik,
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $affected = CPPublication::where('id', $request->id_cp)->update($data);

            if ($affected) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Update CP Publication ID = '.$request->id_cp,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'CP updated');                
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Update CP Publication ID = '.$request->id_cp,
                    'stat' => 'error'
                );
                Logs::create($logInfo);
                return ResponseFormatter::error([], 'Updated Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('update');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Update CP Publication ID = '.$request->id_cp,
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Updated Failed', 400);
        }
    }

    public function delete($id)
    {
        try {
            $affected = CPPublication::where('id', $id)->delete();

            if ($affected) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Delete CP Publication ID = '.$id,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success([
                    'id'=>$id
                ], 'CP deleted');  
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Delete CP Publication ID = '.$id,
                    'stat' => 'error'
                );
                Logs::create($logInfo);
                return ResponseFormatter::error([], 'Delete Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('delete');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Delete CP Publication ID = '.$id,
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Delete Failed', 400);
        }
    }

    public function downloadFile($file)
    {
        return response()->download(storage_path('/storage/app/files/'.$file));
    }

    public function getCPbyMentor(Request $request)
    {
        if (auth()->user()->id_user_role == '2' || auth()->user()->id_user_role == '4'){
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => 'Role not suitable',
            ], 'Get CP Failed', 400);
        } 
        $validation = Validator::make(
            $request->all(),
            [
                'page' => ['required', 'numeric'],
                'limit' => ['required', 'numeric'],
                'status' => ['required', 'string'],
            ]
        );
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }
        try {
            $data = CPPublication::queryForMentor()->where('u.mentor', auth()->user()->nim_nik);
            if ($request->search){
                $data = CPPublication::searchFilter($data, $request->search);
            }
            $data = CPPublication::statusFilter($data, $request->status);
            $data = $data->orderBy('cp.created_at', 'desc');
            $limit = $request->limit;
            if ($request->limit == 0)
                $limit = $data->get()->count();
            $data =  $data->paginate($limit);

            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get CP Publication by Mentor',
                'stat' => 'success'
            );
            Logs::create($logInfo);

            return ResponseFormatter::success($data, 'Get CP by User');
        } catch (\Throwable  $e) {
            Log::debug('getCP');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get CP Publication by Mentor',
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Get CP Failed', 400);
        }
    }

    public function approve(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id_cp' => ['required', 'exists:t_cp_publication,id'],
            // 'approve' => ['required', 'string'],            
        ]);
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {            
            $data = array(
                'approve' => 'A',
                'updated_by' => auth()->user()->nim_nik,
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $affected = CPPublication::where('id', $request->id_cp)->update($data);

            if ($affected) {
                $getCP = CPPublication::queryForMentor()->where('cp.id', $request->id_cp)->first();
                if(!empty($getCP->email)){
                    $email_to = $getCP->email;
                }else{
                    $email_to = $getCP->email2;
                }
                $email_info = array(
                    'nama'  => $getCP->mhs_name,
                    'nim'   => $getCP->nim_nik,
                    'activity_name' => $getCP->activity_name,
                    'cp_name' => 'Publikasi',
                    'updated_at' => $getCP->updated_at, 
                );
                $email = new EmailController();
                $sendStatus = $email->sendEmailNotifApprove($email_to, $email_info);
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Approve CP Publication by Mentor ID='.$request->id_cp,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'CP Approve');                
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Approve CP Publication by Mentor ID='.$request->id_cp,
                    'stat' => 'error'
                );
                Logs::create($logInfo);
                return ResponseFormatter::error([], 'Approval Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('approve');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Approve CP Publication by Mentor ID='.$request->id_cp,
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Approval Failed', 400);
        }
    }

    public function reject(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id_cp' => ['required', 'exists:t_cp_publication,id'],
            'reject_text' => ['required', 'string'],            
        ]);
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {            
            $data = array(
                'approve' => 'R',
                'reject_text' => $request->reject_text,
                'updated_by' => auth()->user()->nim_nik,
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $affected = CPPublication::where('id', $request->id_cp)->update($data);

            if ($affected) {
                $getCP = CPPublication::queryForMentor()->where('cp.id', $request->id_cp)->first();
                if(!empty($getCP->email)){
                    $email_to = $getCP->email;
                }else{
                    $email_to = $getCP->email2;
                }
                $email_info = array(
                    'nama'  => $getCP->mhs_name,
                    'nim'   => $getCP->nim_nik,
                    'activity_name' => $getCP->activity_name,
                    'cp_name' => 'Publikasi',
                    'reject_text' => addslashes(trim($getCP->reject_text)),
                    'updated_at' => $getCP->updated_at, 
                );
                $email = new EmailController();
                $sendStatus = $email->sendEmailNotifReject($email_to, $email_info);
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Reject CP Publication by Mentor ID='.$request->id_cp,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'CP Reject');                
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Reject CP Publication by Mentor ID='.$request->id_cp,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::error([], 'Rejection Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('reject');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Reject CP Publication by Mentor ID='.$request->id_cp,
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Rejection Failed', 400);
        }
    }
}
