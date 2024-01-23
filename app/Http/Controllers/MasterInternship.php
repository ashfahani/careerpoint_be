<?php

namespace App\Http\Controllers;

use App\Models\MLevelInternship;
use App\Models\MRoleInternship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MasterInternship extends Controller
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
            return response()->json(
                ['error' => $validation->errors()],
                400
            );
        }

        try {
            $data = MLevelInternship::baseQuery();
            if ($request->search)
                $data = MLevelInternship::searchFilter($data, $request->search);
            $data = MLevelInternship::statusFilter($data, $request->status);
            $data =  $data->orderBy('id', 'asc');
            $limit = $request->limit;
            if ($request->limit == 0)
                $limit = $data->get()->count();
            $data =  $data->paginate($limit);

            return response()->json($data);
        } catch (\Throwable  $e) {
            Log::debug('getLevel');
            Log::debug($e);

            return response()->json([
                'errorMessage' => 'Error! Contact IT Dev',
            ], 400);
        }
    }

    public function getLevelDetails($id)
    {
        try {
            if (!isset($id)) return response()->json([
                'message' => "Contact IT Dev"
            ], 400);
            $res = MLevelInternship::baseQuery()
                ->where('id', '=', $id)
                ->get();
            return response()->json($res);
        } catch (\Throwable  $e) {
            Log::debug('getLevelDetails');
            Log::debug($e);
            return response()->json([
                'errorMessage' => 'Error! Contact IT Dev',
            ], 400);
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
            return response()->json([
                'error' => $validation->errors()
            ], 422);
        }

        try {
            $data = array(
                'name' => $request->name,
                'id_activity_category' => $request->id_activity_category,
                'score' => $request->score,
                'created_by' =>auth()->user()->id,
                'created_at' =>date('Y-m-d H:i:s'),
            );

            $master = MLevelInternship::create($data);

            if ($master) {
                return response()->json([
                    'message' => 'Master Committee Level created',
                    // 'data' => $data
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Create Failed'
                ], 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('addLevel');
            Log::debug($e);
            return response()->json([
                'message' => 'Error! Contact IT Dev',
            ], 400);
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
            return response()->json([
                'error' => $validation->errors()
            ], 422);
        }

        try {
            $data = array(
                'name' => $request->name,
                'id_activity_category' => $request->id_activity_category,
                'score' => $request->score,
                'na' => $request->na,   
                'updated_by' =>auth()->user()->id,
                'updated_at' =>date('Y-m-d H:i:s'), 
            );

            $affected = MLevelInternship::where('id', $request->id)->update($data);

            if ($affected) {
                return response()->json([
                    'message' => 'Master Committee Level updated',
                    // 'idMaterialHead' => $request->idMaterialHead,
                    // 'data' => $data
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Update Failed',
                ], 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('updateLevel');
            Log::debug($e);
            return response()->json([
                'message' => 'Error! Contact IT Dev',
            ], 400);
        }
    }

    public function deleteLevel($id)
    {
        try {
            $affected = MLevelInternship::where('id', $id)->delete();

            if ($affected) {
                return response()->json([
                    'message' => 'Master Committee Level deleted',
                    'id' => $id
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Delete Failed',
                ], 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('deleteLevel');
            Log::debug($e);
            return response()->json([
                'message' => 'Error! Contact IT Dev',
            ], 400);
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
            return response()->json(
                ['error' => $validation->errors()],
                400
            );
        }

        try {
            $data = MRoleInternship::baseQuery();
            if ($request->search)
                $data = MRoleInternship::searchFilter($data, $request->search);
            $data = MRoleInternship::statusFilter($data, $request->status);
            $data =  $data->orderBy('id', 'asc');
            $limit = $request->limit;
            if ($request->limit == 0)
                $limit = $data->get()->count();
            $data =  $data->paginate($limit);

            return response()->json($data);
        } catch (\Throwable  $e) {
            Log::debug('getLevel');
            Log::debug($e);

            return response()->json([
                'errorMessage' => 'Error! Contact IT Dev',
            ], 400);
        }
    }

    public function getRoleDetails($id)
    {
        try {
            if (!isset($id)) return response()->json([
                'message' => "Contact IT Dev"
            ], 400);
            $res = MRoleInternship::baseQuery()
                ->where('id', '=', $id)
                ->get();
            return response()->json($res);
        } catch (\Throwable  $e) {
            Log::debug('getRoleDetails');
            Log::debug($e);
            return response()->json([
                'errorMessage' => 'Error! Contact IT Dev',
            ], 400);
        }
    }

    public function addRole(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => ['required'],
            'score' => ['required'],
        ]);

        if ($validation->fails()) {
            return response()->json([
                'error' => $validation->errors()
            ], 422);
        }

        try {
            $data = array(
                'name' => $request->name,
                'score' => $request->score,
                'created_by' =>auth()->user()->id,
                'created_at' =>date('Y-m-d H:i:s'),
            );

            $master = MRoleInternship::create($data);

            if ($master) {
                return response()->json([
                    'message' => 'Master Committee Role created',
                    // 'data' => $data
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Create Failed'
                ], 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('addRole');
            Log::debug($e);
            return response()->json([
                'message' => 'Error! Contact IT Dev',
            ], 400);
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
            return response()->json([
                'error' => $validation->errors()
            ], 422);
        }

        try {
            $data = array(
                'name' => $request->name,
                'score' => $request->score,
                'na' => $request->na,    
                'updated_by' =>auth()->user()->id,
                'updated_at' =>date('Y-m-d H:i:s'),
            );

            $affected = MRoleInternship::where('id', $request->id)->update($data);

            if ($affected) {
                return response()->json([
                    'message' => 'Master Committee Role updated',
                    // 'idMaterialHead' => $request->idMaterialHead,
                    // 'data' => $data
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Update Failed',
                ], 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('updateRole');
            Log::debug($e);
            return response()->json([
                'message' => 'Error! Contact IT Dev',
            ], 400);
        }
    }

    public function deleteRole($id)
    {
        try {
            $affected = MRoleInternship::where('id', $id)->delete();

            if ($affected) {
                return response()->json([
                    'message' => 'Master Committee Role deleted',
                    'id' => $id
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Delete Failed',
                ], 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('deleteRole');
            Log::debug($e);
            return response()->json([
                'message' => 'Error! Contact IT Dev',
            ], 400);
        }
    }
}