<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Imports\ImportCP;
use App\Models\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class UploadController extends Controller
{
    public function uploadInternal(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'file' => ['required', 'file', 'max:2563', 'mimes:xls,xlsx'],     
            ]
        );
        if ($validation->fails()) {
            return ResponseFormatter::error($validation->errors(), 'Validation Error!');
        }

        try {
            $insert = Excel::import(new ImportCP, $request->file('file'));
            // return response($insert);
            // exit();

            if ($insert) {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Add CP Internal by Excel',
                    'stat' => 'success'
                );
                Logs::create($logInfo);
                return ResponseFormatter::success([], 'CP Internal Registered by Excel');
            } else {
                $logInfo = array(
                    'user' => auth()->user()->nim_nik,
                    'activity' => 'Add CP Internal by Excel',
                    'stat' => 'error'
                );
                Logs::create($logInfo);
                return ResponseFormatter::error([], 'Registered Internal by Excel Failed', 422);
            }
        } catch (\Throwable  $e) {
            Log::debug('add');
            Log::debug($e);
            $logInfo = array(
                'user' => auth()->user()->nim_nik,
                'activity' => 'Add CP',
                'stat' => 'error'
            );
            Logs::create($logInfo);
            return ResponseFormatter::error([
                'message' => 'Error! Contact IT Dev',
                'error' => $e->getMessage(),
            ], 'Registered Internal by Excel Failed', 400);
        }
    }
}
