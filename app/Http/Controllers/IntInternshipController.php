<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\CPInternship;
use App\Models\IntMemberInternship;
use App\Models\IntInternship;
use App\Models\Logs;
use App\Models\MLevelInternship;
use App\Models\MRoleInternship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class IntInternshipController extends Controller
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
            $data = IntInternship::baseQuery();
            if ($request->search){
                $data = IntInternship::searchFilter($data, $request->search);
            }
            $data = IntInternship::statusFilter($data, $request->status);
            $data =  $data->orderBy('cp.created_at', 'desc');
            $limit = $request->limit;
            if ($request->limit == 0)
                $limit = $data->get()->count();
            $data =  $data->paginate($limit);

            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get CP Internship Internal',
                'stat' => 'success'
            );
            Logs::create($logInfo);

            return ResponseFormatter::success($data, 'Get CP Internal');
        } catch (\Throwable  $e) {
            Log::debug('getCP');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get CP Internship Internal',
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Get CP Internal Failed', 400);
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
            $res = IntInternship::baseQuery()
                ->where('cp.id', '=', $id)
                ->get();
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get Details CP Internship Internal ID = '.$id,
                'stat' => 'success'
            );
            Logs::create($logInfo);
            return ResponseFormatter::success($res, 'Get CP Internal Details');
        } catch (\Throwable  $e) {
            Log::debug('getCPDetails');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get Details CP Internship Internal ID = '.$id,
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Get CP Internal Detail Failed', 400);
        }
    }

    public function add(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'id_activity_type' => ['exists:m_activity_type,id'],
                'activity_name' => ['required', 'string', 'max:255'],
                'initial_period' => ['required', 'date_format:Y-m-d'],
                'final_period' => ['required', 'date_format:Y-m-d', 'after_or_equal:initial_period'],
                'id_pic' => ['nullable', 'exists:m_users,nim_nik'],
                'id_supervisor' => ['nullable', 'exists:m_users,nim_nik'],
                'file' => ['nullable', 'file', 'max:2563', 'mimes:jpg,png,pdf,doc,docx'],     
            ]
        );
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $file = '';
            $file_name = '';
            $file_save = '';
            $file_type = '';
            if($request->hasFile('file')){
                $file = $request->file('file');
                $file_name = auth()->user()->nim_nik."_".$file->getClientOriginalName();
                $file_save = "internship/".$file_name;
                $file_type = $file->getClientOriginalExtension();
                $file->storeAs('files/internship', $file_name);
            }
            
            $data = array(
                'id_activity_type' => $request->id_activity_type,
                'id_activity_category' => '1',  // 1. Internal      2. External
                'activity_name' => addslashes(trim($request->activity_name)),
                'activity_purpose' => addslashes(trim($request->activity_purpose)),
                'initial_period' => date('Y-m-d', strtotime($request->initial_period)), 
                'final_period' => date('Y-m-d', strtotime($request->final_period)),
                'organizer_name' => $request->organizer_name,
                'organizer_location' => $request->organizer_location,
                'id_pic' => $request->id_pic,
                'id_supervisor' => $request->id_supervisor,
                'file' => $file_save,
                'file_type' => $file_type,
                'created_by' => auth()->user()->nim_nik,
                'created_at' => date('Y-m-d H:i:s'),
            );

            $insert = IntInternship::insertGetId($data);

            if ($insert) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Add CP Internship ID = '.$insert,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'CP Internal Registered');
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Add CP Internship',
                    'stat' => 'error'
                );
                Logs::create($logInfo);
                return ResponseFormatter::error([], 'Registered Internal Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('add');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Add CP Internship',
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Registered Internal Failed', 400);
        }
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id_cp' => ['required', 'exists:t_internal_internship,id'],
            'id_activity_type' => ['exists:m_activity_type,id'],
            'activity_name' => ['required', 'string', 'max:255'],
            'initial_period' => ['required', 'date_format:Y-m-d'],
            'final_period' => ['required', 'date_format:Y-m-d', 'after_or_equal:initial_period'],
            'id_pic' => ['nullable', 'exists:m_users,nim_nik'],
            'id_supervisor' => ['nullable', 'exists:m_users,nim_nik'],
            'file' => ['nullable', 'file', 'max:2563', 'mimes:jpg,png,pdf,doc,docx'],             
        ]);

        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $file = '';
            $file_name = '';
            $file_save = '';
            $file_type = '';
            if($request->hasFile('file')){
                $file = $request->file('file');
                $file_name = auth()->user()->nim_nik."_".$file->getClientOriginalName();
                $file_save = "internship/".$file_name;
                $file_type = $file->getClientOriginalExtension();
                $file->storeAs('files/internship', $file_name);
            }
            
            $data = array(
                'id_activity_type' => $request->id_activity_type,
                'activity_name' => addslashes(trim($request->activity_name)),
                'activity_purpose' => addslashes(trim($request->activity_purpose)),
                'initial_period' => date('Y-m-d', strtotime($request->initial_period)), 
                'final_period' => date('Y-m-d', strtotime($request->final_period)),
                'organizer_name' => $request->organizer_name,
                'organizer_location' => $request->organizer_location,
                'id_pic' => $request->id_pic,
                'id_supervisor' => $request->id_supervisor,
                'file' => $file_save,
                'file_type' => $file_type,
                'updated_by' => auth()->user()->nim_nik,
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $affected = IntInternship::where('id', $request->id_cp)->update($data);

            if ($affected) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Update CP Internship ID = '.$request->id_cp,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'CP Internal updated');                
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Update CP Internship Internal ID = '.$request->id_cp,
                    'stat' => 'error'
                );
                Logs::create($logInfo);
                return ResponseFormatter::error([], 'Updated Internal Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('update');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Update CP Internship Internal ID = '.$request->id_cp,
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Updated Internal Failed', 400);
        }
    }

    public function delete($id)
    {
        try {
            $affected = IntInternship::where('id', $id)->delete();
            $affected2 = IntMemberInternship::where('id_internal_internship', $id)->delete();

            if ($affected>0 || $affected2>0) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Delete CP Internship Internal ID = '.$id,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success([
                    'id'=>$id
                ], 'CP Internal deleted');  
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Delete CP Internship Internal ID = '.$id,
                    'stat' => 'error'
                );
                Logs::create($logInfo);
                return ResponseFormatter::error([], 'Delete Internal Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('delete');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Delete CP Internship Internal ID = '.$id,
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Delete Internal Failed', 400);
        }
    }

    public function downloadFile($file)
    {
        return response()->download(storage_path('/storage/app/files/'.$file));
    }

    public function finalize(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id_cp' => ['required', 'exists:t_internal_internship,id'],
        ]);
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {            
            $data = array(
                'final' => 'Y',
                'updated_by' => auth()->user()->nim_nik,
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $affected = IntInternship::where('id', $request->id_cp)->update($data);

            if ($affected) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Finalize CP Internship by Mentor ID='.$request->id_cp,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'CP Finalize');                
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Finalize CP Internship by Mentor ID='.$request->id_cp,
                    'stat' => 'error'
                );
                Logs::create($logInfo);
                return ResponseFormatter::error([], 'Approval Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('finalize');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Finalize CP Internship by Mentor ID='.$request->id_cp,
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Approval Failed', 400);
        }
    }

    public function approve(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id_cp' => ['required', 'exists:t_internal_internship,id'],
        ]);
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {          
            $listCP = IntInternship::getCPMember($request->id_cp);
            foreach($listCP as $r){
                $CPinfo = array(
                    "id_user"               => $r->id_user,
                    "id_activity_type"      => $r->id_activity_type,
                    "activity_name"         => $r->activity_name,
                    "id_activity_category"  => $r->id_activity_category,
                    "initial_period"        => $r->initial_period,
                    "final_period"          => $r->final_period,
                    "period"                => date("Y-m-d", strtotime($r->initial_period))." s/d ".date("Y-m-d", strtotime($r->final_period)),
                    "organizer_name"        => $r->organizer_name,
                    "organizer_location"    => $r->organizer_location,
                    "id_level"              => $r->id_level,
                    "id_role"               => $r->id_role,
                    "score"                 => $r->score,
                    "approve"               => 'A',
                    "na"                    => 'N',
                    "file"                  => $r->file,
                    "file_type"             => $r->file_type,
                    "id_internal_internship" => $r->id,
                    "created_at"            => date("Y-m-d H:i:s"),
                    "created_by"            => auth()->user()->nim_nik
                );

                $insertInt = CPInternship::insertGetId($CPinfo);
            }  
            $data = array(
                'final' => 'N',
                'approve' => 'A',
                'updated_by' => auth()->user()->nim_nik,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $affected = IntInternship::where('id', $request->id_cp)->update($data);

            $data2 = array(
                'approve' => 'A',
                'updated_by' => auth()->user()->nim_nik,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $affected2 = IntMemberInternship::where('id', $request->id_cp)->update($data2);

            if ($affected>0 || $affected2>0) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Approve CP Internship by Mentor ID='.$request->id_cp,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'CP Approve');                
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Approve CP Internship by Mentor ID='.$request->id_cp,
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
                'activity' => 'Approve CP Internship by Mentor ID='.$request->id_cp,
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
            'id_cp' => ['required', 'exists:t_internal_internship,id'],
            'reject_text' => ['required', 'string'],            
        ]);
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {            
            $data = array(
                'final' => 'N',
                'approve' => 'R',
                'reject_text' => $request->reject_text,
                'updated_by' => auth()->user()->nim_nik,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $affected = IntInternship::where('id', $request->id_cp)->update($data);

            $data2 = array(
                'approve' => 'R',
                'updated_by' => auth()->user()->nim_nik,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $affected2 = IntMemberInternship::where('id', $request->id_cp)->update($data2);

            if ($affected>0 || $affected2>0) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Reject CP Internship by Mentor ID='.$request->id_cp,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'CP Reject');                
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Reject CP Internship by Mentor ID='.$request->id_cp,
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
                'activity' => 'Reject CP Internship by Mentor ID='.$request->id_cp,
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Rejection Failed', 400);
        }
    }

    public function getAllCPMember(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'page' => ['required', 'numeric'],
                'limit' => ['required', 'numeric'],
                'status' => ['required', 'string'],
                'id_cp' => ['required', 'exists:t_internal_internship,id']
            ]
        );
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $data = IntMemberInternship::baseQuery();
            if ($request->search){
                $data = IntMemberInternship::searchFilter($data, $request->search);
            }
            $data = IntMemberInternship::statusFilter($data, $request->status);
            $data = $data->where('cp.id_internal_internship', $request->id_cp);
            $data = $data->orderBy('cp.created_at', 'desc');
            $limit = $request->limit;
            if ($request->limit == 0)
                $limit = $data->get()->count();
            $data =  $data->paginate($limit);

            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get CP Internship Internal Member',
                'stat' => 'success'
            );
            Logs::create($logInfo);

            return ResponseFormatter::success($data, 'Get CP Internal Member');
        } catch (\Throwable  $e) {
            Log::debug('getCP');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get CP Internship Internal Member',
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Get CP Internal Member Failed', 400);
        }
    }

    public function addMember(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id_cp' => ['required', 'exists:t_internal_internship,id'],
            'id_user' => ['required', 'exists:m_users,nim_nik'],
            'id_level' => ['required', 'exists:m_level_internship,id'],
            'id_role' => ['required', 'exists:m_role_internship,id'],            
        ]);
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $l_score = MLevelInternship::getScore($request->id_level)->first();
            $r_score = MRoleInternship::getScore($request->id_role)->first();
            if(empty($l_score) || empty($r_score)){
                return ResponseFormatter::error([], 'Level or Role Score are empty!', 404);
            }
            $score = floatval($l_score->score) * floatval($r_score->score);
            $CPDetail = IntInternship::getCPDetail($request->id_cp)->first();

            $data = array(
                'id_internal_internship' => $request->id_cp,
                'id_user' => $request->id_user,
                'initial_period' => date('Y-m-d', strtotime($CPDetail->initial_period)), 
                'final_period' => date('Y-m-d', strtotime($CPDetail->final_period)),
                'id_level' => $request->id_level,
                'id_role' => $request->id_role,
                'score' => $score,
                'role_description' => addslashes(trim($request->role_description)),
                'created_by' => auth()->user()->nim_nik,
                'created_at' => date('Y-m-d H:i:s'),
            );

            $insert = IntMemberInternship::insertGetId($data);

            if ($insert) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Add Member of CP Internship ID = '.$request->id_cp,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'Add CP Member');                
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Add Member of CP Internship ID = '.$request->id_cp,
                    'stat' => 'error'
                );
                Logs::create($logInfo);
                return ResponseFormatter::error([], 'Add CP Member Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('addMember');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Add Member CP Internship ID = '.$request->id_cp,
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Add Member Failed', 400);
        }
    }

    public function updateMember(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id_member' => ['required', 'exists:t_int_internship_member,id'],
            // 'id_cp' => ['required', 'exists:t_internal_internship,id'],
            'id_user' => ['required', 'exists:m_users,nim_nik'],
            'id_level' => ['required', 'exists:m_level_internship,id'],
            'id_role' => ['required', 'exists:m_role_internship,id'],            
        ]);
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $l_score = MLevelInternship::getScore($request->id_level)->first();
            $r_score = MRoleInternship::getScore($request->id_role)->first();
            if(empty($l_score) || empty($r_score)){
                return ResponseFormatter::error([], 'Level or Role Score are empty!', 404);
            }
            $score = floatval($l_score->score) * floatval($r_score->score);
            
            $data = array(
                // 'id_internal_internship' => $request->id_cp,
                'id_user' => $request->id_user,
                'id_level' => $request->id_level,
                'id_role' => $request->id_role,
                'score' => $score,
                'role_description' => addslashes(trim($request->role_description)),
                'na' => $request->na,
                'updated_by' => auth()->user()->nim_nik,
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $affected = IntMemberInternship::where('id', $request->id_member)->update($data);

            if ($affected) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Updated Member of CP Internship ID = '.$request->id_cp,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'Update CP Member');                
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Update Member of CP Internship ID = '.$request->id_cp,
                    'stat' => 'error'
                );
                Logs::create($logInfo);
                return ResponseFormatter::error([], 'Update CP Member Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('updateMember');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Update Member CP Internship ID = '.$request->id_cp,
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Update Member Failed', 400);
        }
    }

    public function deleteMember($id)
    {
        try {
            $affected = IntMemberInternship::where('id', $id)->delete();

            if ($affected) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Delete CP Internship Internal Member ID = '.$id,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success([
                    'id'=>$id
                ], 'CP Member deleted');  
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Delete CP Internship Internal Member ID = '.$id,
                    'stat' => 'error'
                );
                Logs::create($logInfo);
                return ResponseFormatter::error([], 'Delete Member Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('delete');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Delete CP Internship Internal Member ID = '.$id,
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Delete Member Failed', 400);
        }
    }
}
