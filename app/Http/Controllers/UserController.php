<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'nim_nik' => 'required|string',
                'name' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
                'id_user_role' => 'required|numeric|exists:m_user_role,id',
                'id_prodi' => 'required|numeric|exists:m_prodi,id',
                'email' => 'required|string|email:rfc,dns',
                'email2' => 'string|email:rfc,dns',                
            ]
        );
        if ($validation->fails()) {
            return response()->json(
                ['error' => $validation->errors()],
                400
            );
        }

        try {
            $data = array(
                'nim_nik' => $request->nim_nik,
                'name' => $request->name,
                'password' =>Hash::make($request->password),
                'id_user_role' => $request->id_user_role,
                'id_prodi' => $request->id_prodi,
                'tahun_id' => $request->tahun_id,
                'email' => $request->email,
                'email2' => $request->email2,
                'mentor' => $request->mentor,
                'created_by' => auth()->user()->id,
                'created_at' => date('Y-m-d H:i:s'),
            );

            $user = User::create($data);

            if ($user) {
                return ResponseFormatter::success( 
                );
                return response()->json([
                    'message' => 'Master User Registered',
                    // 'data' => $data
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Registered Failed'
                ], 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('Register');
            Log::debug($e);
            return response()->json([
                'message' => 'Error! Contact IT Dev',
            ], 400);
        }
    }

    public function getUsers(Request $request)
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
            $data = User::baseQuery();
            if ($request->search)
                $data = User::searchFilter($data, $request->search);
            $data = User::statusFilter($data, $request->status);
            $data =  $data->orderBy('nim_nik', 'asc');
            $limit = $request->limit;
            if ($request->limit == 0)
                $limit = $data->get()->count();
            $data =  $data->paginate($limit);

            return response()->json($data);
        } catch (\Throwable  $e) {
            Log::debug('getUsers');
            Log::debug($e);

            return response()->json([
                'errorMessage' => 'Error! Contact IT Dev',
            ], 400);
        }
    }

    public function getUserDetails($id)
    {
        try {
            if (!isset($id)) return response()->json([
                'message' => "Contact IT Dev"
            ], 400);
            $res = User::baseQuery()
                ->where('u.nim_nik', '=', $id)
                ->get();
            return response()->json($res);
        } catch (\Throwable  $e) {
            Log::debug('getUserDetails');
            Log::debug($e);
            return response()->json([
                'errorMessage' => 'Error! Contact IT Dev',
            ], 400);
        }
    }

    public function updateUser(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nim_nik' => 'required|string',
            'name' => 'required|string',
            'id_user_role' => 'required|numeric|exists:m_user_role,id',
            'id_prodi' => 'required|numeric|exists:m_prodi,id',
            'email' => 'required|string|email:rfc,dns',
            'email2' => 'string|email:rfc,dns',            
        ]);

        if ($validation->fails()) {
            return response()->json([
                'error' => $validation->errors()
            ], 422);
        }

        try {
            $data = array(
                'name' => $request->name,
                'password' =>Hash::make($request->password),
                'id_user_role' => $request->id_user_role,
                'id_prodi' => $request->id_prodi,
                'tahun_id' => $request->tahun_id,
                'email' => $request->email,
                'email2' => $request->email2,
                'mentor' => $request->mentor,
                'na' => $request->na,
                'updated_by' => auth()->user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $affected = User::where('nim_nik', $request->nim_nik)->update($data);

            if ($affected) {
                return response()->json([
                    'message' => 'Master User updated',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Update Failed',
                ], 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('updateUser');
            Log::debug($e);
            return response()->json([
                'message' => 'Error! Contact IT Dev',
            ], 400);
        }
    }

    public function deleteUser($id)
    {
        try {
            $affected = User::where('nim_nik', $id)->delete();

            if ($affected) {
                return response()->json([
                    'message' => 'Master User deleted',
                    'id' => $id
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Delete Failed',
                ], 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('deleteUser');
            Log::debug($e);
            return response()->json([
                'message' => 'Error! Contact IT Dev',
            ], 400);
        }
    }
}
