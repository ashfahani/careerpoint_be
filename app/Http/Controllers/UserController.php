<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'nim_nik' => ['required', 'string', 'max:50', 'unique:m_users,nim_nik'],
                'name' => ['required', 'string', 'max:255'],
                'password' => ['required', Rules\Password::defaults(), 'string', 'min:8'],
                'id_user_role' => ['exists:m_user_role,id'],
                'id_prodi' => ['exists:m_prodi,id'],
                'email' => ['required', 'string', 'email'],
                'email2' => ['string', 'email', 'nullable']              
            ]
        );
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $data = array(
                'nim_nik' => $request->nim_nik,
                'name' => addslashes(trim($request->name)),
                'password' =>Hash::make($request->password),
                'id_user_role' => $request->id_user_role,
                'id_prodi' => $request->id_prodi,
                'tahun_id' => $request->tahun_id,
                'email' => $request->email,
                'email2' => $request->email2,
                'mentor' => $request->mentor,
                'created_by' => auth()->user()->nim_nik,
                'created_at' => date('Y-m-d H:i:s'),
            );

            $user = User::create($data);

            if ($user) {
                return ResponseFormatter::success($data, 'Master User Registered');
            } else {
                return ResponseFormatter::error([], 'Registered Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('Register');
            Log::debug($e);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Registered Failed', 400);
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
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $data = User::baseQuery();
            // return response()->json($data->get());
            // exit();
            if ($request->search){
                $data = User::searchFilter($data, $request->search);
            }
            $data = User::statusFilter($data, $request->status);
            $data =  $data->orderBy('nim_nik', 'asc');
            $limit = $request->limit;
            if ($request->limit == 0)
                $limit = $data->get()->count();
            $data =  $data->paginate($limit);

            return ResponseFormatter::success($data, 'Master User');
        } catch (\Throwable  $e) {
            Log::debug('getUsers');
            Log::debug($e);

            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Registered Failed', 400);
        }
    }

    public function getUserDetails($id)
    {
        try {
            if (!isset($id)){
                return ResponseFormatter::error([
                    'message' => 'Error! Contact IT Dev',
                    'error' => 'Parameter is missing',
                ], 'Get User Failed', 400);
            } 
            $res = User::baseQuery()
                ->where('m_users.nim_nik', '=', $id)
                ->get();
            return ResponseFormatter::success($res, 'Master User Details');
        } catch (\Throwable  $e) {
            Log::debug('getUserDetails');
            Log::debug($e);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Registered Failed', 400);
        }
    }

    public function updateUser(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'password' => [Rules\Password::defaults(), 'string', 'min:8', 'nullable'],
            'id_user_role' => ['exists:m_user_role,id'],
            'id_prodi' => ['exists:m_prodi,id'],
            'email' => ['required', 'string', 'email'],
            'email2' => ['string', 'email', 'nullable']            
        ]);

        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $data = array(
                'name' => addslashes(trim($request->name)),
                'password' =>Hash::make($request->password),
                'id_user_role' => $request->id_user_role,
                'id_prodi' => $request->id_prodi,
                'tahun_id' => $request->tahun_id,
                'email' => $request->email,
                'email2' => $request->email2,
                'mentor' => $request->mentor,
                'na' => $request->na,
                'updated_by' => auth()->user()->nim_nik,
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $affected = User::where('nim_nik', $request->nim_nik)->update($data);

            if ($affected) {
                return ResponseFormatter::success($data, 'Master User updated');                
            } else {
                return ResponseFormatter::error([], 'Updated Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('updateUser');
            Log::debug($e);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Registered Failed', 400);
        }
    }

    public function deleteUser($id)
    {
        try {
            $affected = User::where('nim_nik', $id)->delete();

            if ($affected) {
                return ResponseFormatter::success([
                    'nim_nik'=>$id
                ], 'Master User deleted');  
            } else {
                return ResponseFormatter::error([], 'Delete Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('deleteUser');
            Log::debug($e);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Registered Failed', 400);
        }
    }
}
