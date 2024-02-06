<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\CPCommittee;
use App\Models\CPCompetition;
use App\Models\CPSeminar;
use App\Models\IntMemberCommittee;
use App\Models\IntCommittee;
use App\Models\IntParticipantCommittee;
use App\Models\Logs;
use App\Models\MLevelCommittee;
use App\Models\MLevelCompetition;
use App\Models\MLevelSeminar;
use App\Models\MRoleCommittee;
use App\Models\MRoleCompetition;
use App\Models\MRoleSeminar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class IntCommitteeController extends Controller
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
            $data = IntCommittee::baseQuery();
            if ($request->search){
                $data = IntCommittee::searchFilter($data, $request->search);
            }
            $data = IntCommittee::statusFilter($data, $request->status);
            $data =  $data->orderBy('cp.created_at', 'desc');
            $limit = $request->limit;
            if ($request->limit == 0)
                $limit = $data->get()->count();
            $data =  $data->paginate($limit);

            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get CP Committee Internal',
                'stat' => 'success'
            );
            Logs::create($logInfo);

            return ResponseFormatter::success($data, 'Get CP Internal');
        } catch (\Throwable  $e) {
            Log::debug('getCP');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get CP Committee Internal',
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
            $res = IntCommittee::baseQuery()
                ->where('cp.id', '=', $id)
                ->get();
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get Details CP Committee Internal ID = '.$id,
                'stat' => 'success'
            );
            Logs::create($logInfo);
            return ResponseFormatter::success($res, 'Get CP Internal Details');
        } catch (\Throwable  $e) {
            Log::debug('getCPDetails');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get Details CP Committee Internal ID = '.$id,
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
                'id_committee_type' => ['required', 'exists:m_committee_type,id'],
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
                $file_save = "committee/".$file_name;
                $file_type = $file->getClientOriginalExtension();
                $file->storeAs('files/committee', $file_name);
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
                'id_committee_type' => $request->id_committee_type,
                'file' => $file_save,
                'file_type' => $file_type,
                'created_by' => auth()->user()->nim_nik,
                'created_at' => date('Y-m-d H:i:s'),
            );

            $insert = IntCommittee::insertGetId($data);

            if ($insert) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Add CP Committee ID = '.$insert,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'CP Internal Registered');
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Add CP Committee',
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
                'activity' => 'Add CP Committee',
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
            'id_cp' => ['required', 'exists:t_internal_committee,id'],
            'id_activity_type' => ['exists:m_activity_type,id'],
            'activity_name' => ['required', 'string', 'max:255'],
            'initial_period' => ['required', 'date_format:Y-m-d'],
            'final_period' => ['required', 'date_format:Y-m-d', 'after_or_equal:initial_period'],
            'id_committee_type' => ['required', 'exists:m_committee_type,id'],
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
                $file_save = "committee/".$file_name;
                $file_type = $file->getClientOriginalExtension();
                $file->storeAs('files/committee', $file_name);
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
                'id_committee_type' => $request->id_committee_type,
                'file' => $file_save,
                'file_type' => $file_type,
                'updated_by' => auth()->user()->nim_nik,
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $affected = IntCommittee::where('id', $request->id_cp)->update($data);

            if ($affected) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Update CP Committee ID = '.$request->id_cp,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'CP Internal updated');                
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Update CP Committee Internal ID = '.$request->id_cp,
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
                'activity' => 'Update CP Committee Internal ID = '.$request->id_cp,
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
            $affected = IntCommittee::where('id', $id)->delete();
            $affected2 = IntMemberCommittee::where('id_internal_committee', $id)->delete();
            $affected3 = IntParticipantCommittee::where('id_internal_committee', $id)->delete();

            if ($affected>0 || $affected2>0 || $affected3) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Delete CP Committee Internal ID = '.$id,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success([
                    'id'=>$id
                ], 'CP Internal deleted');  
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Delete CP Committee Internal ID = '.$id,
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
                'activity' => 'Delete CP Committee Internal ID = '.$id,
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
            'id_cp' => ['required', 'exists:t_internal_committee,id'],
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

            $affected = IntCommittee::where('id', $request->id_cp)->update($data);

            if ($affected) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Finalize CP Committee by Mentor ID='.$request->id_cp,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'CP Finalize');                
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Finalize CP Committee by Mentor ID='.$request->id_cp,
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
                'activity' => 'Finalize CP Committee by Mentor ID='.$request->id_cp,
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
            'id_cp' => ['required', 'exists:t_internal_committee,id'],
        ]);
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {          
            $listCP = IntCommittee::getCPMember($request->id_cp);
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
                    "id_internal_committee" => $r->id,
                    "created_at"            => date("Y-m-d H:i:s"),
                    "created_by"            => auth()->user()->nim_nik
                );

                if($r->id_committee_type == '1')    // 1. Perlombaan  | 2. Seminar  | 3. Kepanitiaan
                {
                    $insertInt = CPCompetition::insertGetId($CPinfo);
                }
                elseif($r->id_committee_type == '2')
                {
                    $insertInt = CPSeminar::insertGetId($CPinfo);
                }
                elseif($r->id_committee_type == '3')
                {
                    $insertInt = CPCommittee::insertGetId($CPinfo);
                }
                unset($CPinfo);
            }  
            $data = array(
                'final' => 'N',
                'approve' => 'A',
                'updated_by' => auth()->user()->nim_nik,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $affected = IntCommittee::where('id', $request->id_cp)->update($data);

            $data2 = array(
                'approve' => 'A',
                'updated_by' => auth()->user()->nim_nik,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $affected2 = IntMemberCommittee::where('id', $request->id_cp)->update($data2);
            $affected3 = IntParticipantCommittee::where('id', $request->id_cp)->update($data2);

            if ($affected>0 || $affected2>0 || $affected3>0) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Approve CP Committee by Mentor ID='.$request->id_cp,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'CP Approve');                
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Approve CP Committee by Mentor ID='.$request->id_cp,
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
                'activity' => 'Approve CP Committee by Mentor ID='.$request->id_cp,
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
            'id_cp' => ['required', 'exists:t_internal_committee,id'],
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
            $affected = IntCommittee::where('id', $request->id_cp)->update($data);

            $data2 = array(
                'approve' => 'R',
                'updated_by' => auth()->user()->nim_nik,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $affected2 = IntMemberCommittee::where('id', $request->id_cp)->update($data2);
            $affected3 = IntParticipantCommittee::where('id', $request->id_cp)->update($data2);

            if ($affected>0 || $affected2>0 || $affected3>0) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Reject CP Committee by Mentor ID='.$request->id_cp,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'CP Reject');                
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Reject CP Committee by Mentor ID='.$request->id_cp,
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
                'activity' => 'Reject CP Committee by Mentor ID='.$request->id_cp,
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
                'id_cp' => ['required', 'exists:t_internal_committee,id']
            ]
        );
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $data = IntMemberCommittee::baseQuery();
            if ($request->search){
                $data = IntMemberCommittee::searchFilter($data, $request->search);
            }
            $data = IntMemberCommittee::statusFilter($data, $request->status);
            $data = $data->where('cp.id_internal_committee', $request->id_cp);
            $data = $data->orderBy('cp.created_at', 'desc');
            $limit = $request->limit;
            if ($request->limit == 0)
                $limit = $data->get()->count();
            $data =  $data->paginate($limit);

            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get CP Committee Internal Member',
                'stat' => 'success'
            );
            Logs::create($logInfo);

            return ResponseFormatter::success($data, 'Get CP Internal Member');
        } catch (\Throwable  $e) {
            Log::debug('getCP');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get CP Committee Internal Member',
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
            'id_cp' => ['required', 'exists:t_internal_committee,id'],
            'id_user' => ['required', 'exists:m_users,nim_nik'],
            'id_level' => ['required', 'exists:m_level_committee,id'],
            'id_role' => ['required', 'exists:m_role_committee,id'],            
        ]);
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {           
            $l_score = MLevelCommittee::getScore($request->id_level)->first();
            $r_score = MRoleCommittee::getScore($request->id_role)->first();
            if(empty($l_score) || empty($r_score)){
                return ResponseFormatter::error([], 'Level or Role Score are empty!', 404);
            }
            $score = floatval($l_score->score) * floatval($r_score->score);
            $CPDetail = IntCommittee::getCPDetail($request->id_cp)->first();

            $data = array(
                'id_internal_committee' => $request->id_cp,
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

            $insert = IntMemberCommittee::insertGetId($data);

            if ($insert) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Add Member of CP Committee ID = '.$request->id_cp,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'Add CP Member');                
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Add Member of CP Committee ID = '.$request->id_cp,
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
                'activity' => 'Add Member CP Committee ID = '.$request->id_cp,
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
            'id_member' => ['required', 'exists:t_int_committee_member,id'],
            // 'id_cp' => ['required', 'exists:t_internal_committee,id'],
            'id_user' => ['required', 'exists:m_users,nim_nik'],
            'id_level' => ['required', 'exists:m_level_committee,id'],
            'id_role' => ['required', 'exists:m_role_committee,id'],            
        ]);
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $l_score = MLevelCommittee::getScore($request->id_level)->first();
            $r_score = MRoleCommittee::getScore($request->id_role)->first();
            if(empty($l_score) || empty($r_score)){
                return ResponseFormatter::error([], 'Level or Role Score are empty!', 404);
            }
            $score = floatval($l_score->score) * floatval($r_score->score);
            
            $data = array(
                // 'id_internal_committee' => $request->id_cp,
                'id_user' => $request->id_user,
                'id_level' => $request->id_level,
                'id_role' => $request->id_role,
                'score' => $score,
                'role_description' => addslashes(trim($request->role_description)),
                'na' => $request->na,
                'updated_by' => auth()->user()->nim_nik,
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $affected = IntMemberCommittee::where('id', $request->id_member)->update($data);

            if ($affected) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Updated Member of CP Committee ID = '.$request->id_cp,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'Update CP Member');                
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Update Member of CP Committee ID = '.$request->id_cp,
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
                'activity' => 'Update Member CP Committee ID = '.$request->id_cp,
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
            $affected = IntMemberCommittee::where('id', $id)->delete();

            if ($affected) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Delete CP Committee Internal Member ID = '.$id,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success([
                    'id'=>$id
                ], 'CP Member deleted');  
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Delete CP Committee Internal Member ID = '.$id,
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
                'activity' => 'Delete CP Committee Internal Member ID = '.$id,
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Delete Member Failed', 400);
        }
    }

    public function getAllCPParticipant(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'page' => ['required', 'numeric'],
                'limit' => ['required', 'numeric'],
                'status' => ['required', 'string'],
                'id_cp' => ['required', 'exists:t_internal_committee,id']
            ]
        );
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $getType = IntCommittee::select('*')->where('id', $request->id_cp)->first();
            if($getType->id_committee_type == '1'){
                $data = IntParticipantCommittee::baseQuery_competition();
                if ($request->search){
                    $data = IntParticipantCommittee::searchFilter($data, $request->search);
                }
                $data = IntParticipantCommittee::statusFilter($data, $request->status);
            }elseif($getType->id_committee_type == '2'){
                $data = IntParticipantCommittee::baseQuery_seminar();
                if ($request->search){
                    $data = IntParticipantCommittee::searchFilter($data, $request->search);
                }
                $data = IntParticipantCommittee::statusFilter($data, $request->status);
            }            
            $data = $data->where('cp.id_internal_committee', $request->id_cp);
            $data = $data->orderBy('cp.created_at', 'desc');
            $limit = $request->limit;
            if ($request->limit == 0)
                $limit = $data->get()->count();
            $data =  $data->paginate($limit);

            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get CP Committee Internal Member',
                'stat' => 'success'
            );
            Logs::create($logInfo);

            return ResponseFormatter::success($data, 'Get CP Internal Member');
        } catch (\Throwable  $e) {
            Log::debug('getCP');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Get CP Committee Internal Member',
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Get CP Internal Member Failed', 400);
        }
    }
    public function addParticipant(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id_cp' => ['required', 'exists:t_internal_committee,id'],          
        ]);
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        $getType = IntCommittee::select('*')->where('id', $request->id_cp)->first();
        if($getType->id_committee_type == '1'){
            $validation = Validator::make($request->all(), [
                'id_user' => ['required', 'exists:m_users,nim_nik'],
                'id_level' => ['required', 'exists:m_level_competition,id'],
                'id_role' => ['required', 'exists:m_role_competition,id'],            
            ]);
            if ($validation->fails()) {
                return ResponseFormatter::error($validation->errors(), 'Validation Error!');
            }

            $l_score = MLevelCompetition::getScore($request->id_level)->first();
            $r_score = MRoleCompetition::getScore($request->id_role)->first();
            if(empty($l_score) || empty($r_score)){
                return ResponseFormatter::error([], 'Level or Role Score are empty!', 404);
            }
            $score = floatval($l_score->score) * floatval($r_score->score);

        }elseif($getType->id_committee_type == '2'){
            $validation = Validator::make($request->all(), [
                'id_user' => ['required', 'exists:m_users,nim_nik'],
                'id_level' => ['required', 'exists:m_level_seminar,id'],
                'id_role' => ['required', 'exists:m_role_seminar,id'],            
            ]);
            if ($validation->fails()) {
                return ResponseFormatter::error($validation->errors(), 'Validation Error!');
            }

            $l_score = MLevelSeminar::getScore($request->id_level)->first();
            $r_score = MRoleSeminar::getScore($request->id_role)->first();
            if(empty($l_score) || empty($r_score)){
                return ResponseFormatter::error([], 'Level or Role Score are empty!', 404);
            }
            $score = floatval($l_score->score) * floatval($r_score->score);

        }            
        

        try {           
            $CPDetail = IntCommittee::getCPDetail($request->id_cp)->first();

            $data = array(
                'id_internal_committee' => $request->id_cp,
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

            $insert = IntParticipantCommittee::insertGetId($data);

            if ($insert) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Add Participant of CP Committee ID = '.$request->id_cp,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'Add CP Participant');                
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Add Participant of CP Committee ID = '.$request->id_cp,
                    'stat' => 'error'
                );
                Logs::create($logInfo);
                return ResponseFormatter::error([], 'Add CP Participant Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('addParticipant');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Add Participant CP Committee ID = '.$request->id_cp,
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Add Participant Failed', 400);
        }
    }

    public function updateParticipant(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id_cp' => ['required', 'exists:t_internal_committee,id'],          
        ]);
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        $getType = IntCommittee::select('*')->where('id', $request->id_cp)->first();
        if($getType->id_committee_type == '1'){
            $validation = Validator::make($request->all(), [
                'id_user' => ['required', 'exists:m_users,nim_nik'],
                'id_level' => ['required', 'exists:m_level_competition,id'],
                'id_role' => ['required', 'exists:m_role_competition,id'],            
            ]);
            if ($validation->fails()) {
                return ResponseFormatter::error($validation->errors(), 'Validation Error!');
            }

            $l_score = MLevelCompetition::getScore($request->id_level)->first();
            $r_score = MRoleCompetition::getScore($request->id_role)->first();
            if(empty($l_score) || empty($r_score)){
                return ResponseFormatter::error([], 'Level or Role Score are empty!', 404);
            }
            $score = floatval($l_score->score) * floatval($r_score->score);

        }elseif($getType->id_committee_type == '2'){
            $validation = Validator::make($request->all(), [
                'id_user' => ['required', 'exists:m_users,nim_nik'],
                'id_level' => ['required', 'exists:m_level_seminar,id'],
                'id_role' => ['required', 'exists:m_role_seminar,id'],            
            ]);
            if ($validation->fails()) {
                return ResponseFormatter::error($validation->errors(), 'Validation Error!');
            }

            $l_score = MLevelSeminar::getScore($request->id_level)->first();
            $r_score = MRoleSeminar::getScore($request->id_role)->first();
            if(empty($l_score) || empty($r_score)){
                return ResponseFormatter::error([], 'Level or Role Score are empty!', 404);
            }
            $score = floatval($l_score->score) * floatval($r_score->score);

        }         

        try {            
            $data = array(
                // 'id_internal_committee' => $request->id_cp,
                'id_user' => $request->id_user,
                'id_level' => $request->id_level,
                'id_role' => $request->id_role,
                'score' => $score,
                'role_description' => addslashes(trim($request->role_description)),
                'na' => $request->na,
                'updated_by' => auth()->user()->nim_nik,
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $affected = IntParticipantCommittee::where('id', $request->id_member)->update($data);

            if ($affected) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Updated Participant of CP Committee ID = '.$request->id_cp,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success($data, 'Update CP Participant');                
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Update Participant of CP Committee ID = '.$request->id_cp,
                    'stat' => 'error'
                );
                Logs::create($logInfo);
                return ResponseFormatter::error([], 'Update CP Participant Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('updateParticipant');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Update Participant CP Committee ID = '.$request->id_cp,
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Update Participant Failed', 400);
        }
    }

    public function deleteParticipant($id)
    {
        try {
            $affected = IntParticipantCommittee::where('id', $id)->delete();

            if ($affected) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Delete CP Committee Internal Participant ID = '.$id,
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success([
                    'id'=>$id
                ], 'CP Participant deleted');  
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Delete CP Committee Internal Participant ID = '.$id,
                    'stat' => 'error'
                );
                Logs::create($logInfo);
                return ResponseFormatter::error([], 'Delete Participant Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('delete');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Delete CP Committee Internal Participant ID = '.$id,
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Delete Participant Failed', 400);
        }
    }
}
