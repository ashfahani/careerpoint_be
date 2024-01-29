<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\MLevelCompetition;
use App\Models\MRoleCompetition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MasterCompetition extends Controller
{
    public function getLevel(Request $request)
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
            $data = MLevelCompetition::baseQuery();
            if ($request->search)
                $data = MLevelCompetition::searchFilter($data, $request->search);
            $data = MLevelCompetition::statusFilter($data, $request->status);
            $data =  $data->orderBy('id', 'asc');
            $limit = $request->limit;
            if ($request->limit == 0)
                $limit = $data->get()->count();
            $data =  $data->paginate($limit);

            return ResponseFormatter::success($data, 'Master Competition Level');
        } catch (\Throwable  $e) {
            Log::debug('getLevel');
            Log::debug($e);

            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Get Level Failed', 400);
        }
    }

    public function getLevelDetails($id)
    {
        try {
            if (!isset($id)){
                return ResponseFormatter::error([
                    'message' => 'Error! Contact IT Dev',
                    'error' => 'Parameter is missing',
                ], 'Get Level Failed', 400);
            } 
            $res = MLevelCompetition::baseQuery()
                ->where('id', '=', $id)
                ->get();
            return ResponseFormatter::success($res, 'Master Competition Level Detail');
        } catch (\Throwable  $e) {
            Log::debug('getLevelDetails');
            Log::debug($e);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Get Level Failed', 400);
        }
    }

    public function addLevel(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => ['required'],
            'id_activity_category' => ['required'],
            'score' => ['required'],
        ]);

        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $data = array(
                'name' => $request->name,
                'id_activity_category' => $request->id_activity_category,
                'score' => $request->score,
                'created_by' =>auth()->user()->nim_nik,
                'created_at' =>date('Y-m-d H:i:s'),
            );

            $master = MLevelCompetition::create($data);

            if ($master) {
                return ResponseFormatter::success($data, 'Master Competition Level created');
            } else {
                return ResponseFormatter::error([], 'Create Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('addLevel');
            Log::debug($e);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Add Level Failed', 400);
        }
    }

    public function updateLevel(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => ['required'],
            'name' => ['required', 'string'],
            'id_activity_category' => ['required'],
            'score' => ['required'],
        ]);

        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $data = array(
                'name' => $request->name,
                'id_activity_category' => $request->id_activity_category,
                'score' => $request->score,
                'na' => $request->na,   
                'updated_by' =>auth()->user()->nim_nik,
                'updated_at' =>date('Y-m-d H:i:s'), 
            );

            $affected = MLevelCompetition::where('id', $request->id)->update($data);

            if ($affected) {
                return ResponseFormatter::success($data, 'Master Competition Level updated');                
            } else {
                return ResponseFormatter::error([], 'Updated Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('updateLevel');
            Log::debug($e);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Update Level Failed', 400);
        }
    }

    public function deleteLevel($id)
    {
        try {
            $affected = MLevelCompetition::where('id', $id)->delete();

            if ($affected) {
                return ResponseFormatter::success([
                    'id'=>$id
                ], 'Master Competition Level deleted');  
            } else {
                return ResponseFormatter::error([], 'Delete Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('deleteLevel');
            Log::debug($e);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Delete Level Failed', 400);
        }
    }

    public function getRole(Request $request)
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
            $data = MRoleCompetition::baseQuery();
            if ($request->search)
                $data = MRoleCompetition::searchFilter($data, $request->search);
            $data = MRoleCompetition::statusFilter($data, $request->status);
            $data =  $data->orderBy('id', 'asc');
            $limit = $request->limit;
            if ($request->limit == 0)
                $limit = $data->get()->count();
            $data =  $data->paginate($limit);

            return ResponseFormatter::success($data, 'Master Competition Role');
        } catch (\Throwable  $e) {
            Log::debug('getLevel');
            Log::debug($e);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Get Role Failed', 400);
        }
    }

    public function getRoleDetails($id)
    {
        try {
            if (!isset($id)){
                return ResponseFormatter::error([
                    'message' => 'Error! Contact IT Dev',
                    'error' => 'Parameter is missing',
                ], 'Get Role Failed', 400);
            } 
            $res = MRoleCompetition::baseQuery()
                ->where('id', '=', $id)
                ->get();
            return ResponseFormatter::success($res, 'Master Competition Role Detail');
        } catch (\Throwable  $e) {
            Log::debug('getRoleDetails');
            Log::debug($e);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Get Role Detail Failed', 400);
        }
    }

    public function addRole(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => ['required'],
            'score' => ['required'],
        ]);

        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $data = array(
                'name' => $request->name,
                'score' => $request->score,
                'created_by' =>auth()->user()->nim_nik,
                'created_at' =>date('Y-m-d H:i:s'),
            );

            $master = MRoleCompetition::create($data);

            if ($master) {
                return ResponseFormatter::success($data, 'Master Competition Role created');
            } else {
                return ResponseFormatter::error([], 'Create Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('addRole');
            Log::debug($e);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Add Role Failed', 400);
        }
    }

    public function updateRole(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => ['required'],
            'name' => ['required', 'string'],
            'score' => ['required'],
        ]);

        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $data = array(
                'name' => $request->name,
                'score' => $request->score,
                'na' => $request->na,    
                'updated_by' =>auth()->user()->nim_nik,
                'updated_at' =>date('Y-m-d H:i:s'),
            );

            $affected = MRoleCompetition::where('id', $request->id)->update($data);

            if ($affected) {
                return ResponseFormatter::success($data, 'Master Competition Role updated');                
            } else {
                return ResponseFormatter::error([], 'Updated Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('updateRole');
            Log::debug($e);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Update Role Failed', 400);
        }
    }

    public function deleteRole($id)
    {
        try {
            $affected = MRoleCompetition::where('id', $id)->delete();

            if ($affected) {
                return ResponseFormatter::success([
                    'id'=>$id
                ], 'Master Competition Role deleted');  
            } else {
                return ResponseFormatter::error([], 'Delete Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('deleteRole');
            Log::debug($e);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Delete Role Failed', 400);
        }
    }
}
