<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use App\Services\AuthServices;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    private $authServices;

    public function __construct(AuthServices $authServices)
    {
        $this->authServices = $authServices;
    }

    public function login(Request $request)
    {
        try{
            $validation = Validator::make(
                $request->all(),
                [
                    'username' => 'required|string',
                    'password' => 'required|string',
                ]
            );
            if ($validation->fails()) {
                return ResponseFormatter::error($validation->error(), 'Validation Error!');
            }
    
            // Check NIK/NIP exist
            $user = User::where('id_user', $request->username)->first();
    
            // Check Password
            if(!$user || !Hash::check($request->password, $user->password, []))
            {
                return ResponseFormatter::error([
                    'message' => 'Username or password does not match!',
                ], 'Authentication failed', 401);
            }
    
            $token = $user->createToken($request->username)->plainTextToken;
            $cookie = cookie(name: 'jwt', value: $token, minutes: 50 * 24);
    
            return ResponseFormatter::success([
                'access_token' => $token,
                'user' => $user
            ], 'Authenticated')->withCookie($cookie);

        }catch (Exception $error){
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Authentication failed', 500);

        }        
    }

    // public function outlookLogin()
    // {
        
    // }
    
    // Menampilan detail user yang saat ini login
    public function currentAccount()
    {
        try {
            return ResponseFormatter::success(Auth::user(), 'Data has been successfully fetched');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Authentication failed', 500);
        }
    }    

    public function logout(Request $request)
    {
        // Get bearer token from the request
        $accessToken = $request->bearerToken();

        // Get access token from database
        $token = PersonalAccessToken::findToken($accessToken);

        // Revoke token
        $token->delete();

        $cookie = Cookie::forget('jwt');
        return ResponseFormatter::success([], 'Logout successfully!')->withCookie($cookie);
    }

    public function forgetPassword(Request $request){
        $validation = Validator::make($request->all(), [ 
            'email' => ['required', 'email'],
        ]);

        if($validation->fails()){
            return ResponseFormatter::error($validation->error(), 'Validation Error!');
        }

        $email = $request->email;
        if(User::where('email', $email)->orWhere('email2', $email)->doesntExist()){
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => 'Email doesn\'t exist',
            ], 'Send email failed', 404);
        }

        try{
            $status = Password::sendResetLink(
                $request->only('email')
            );

            return ResponseFormatter::success($status, 'Please Check Your Email');
        }catch(Exception $e){
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 'Send Email failed', 404);
        }
    }

    public function passwordReset(Request $request){
        try{
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => ['required', Password::defaults()]
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'token'),
                function($user, $password){
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));
                    $user->has_password = true;
                    $user->save();

                    event(new PasswordReset($user));
                }
            );
            
            // $status === Password::PASSWORD_RESET;

            return ResponseFormatter::success($status, 'Your password has change');
        }catch(Exception $e){
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 'Reset password failed', 422);
        }
    }

    public function changePassword(Request $request){
        $validation = Validator::make($request->all(), [
            'newPassword' => ['required', Rules\Password::defaults(), 'string', 'min:8'],
            'confirmPassword' => ['required', 'same:newPassword']
        ]);

        if($validation->fails()){
            return ResponseFormatter::error($validation->error(), 'Validation Error!', 422);
        }
        $oldPassword = $request->oldPassword;
        $newPassword = $request->newPassword;
        $confirmPassword = $request->confirmPassword;

        $user = Auth::guard('api')->user();
        if($user->has_password){
            if(!Hash::check($oldPassword, $user->password)){
                return ResponseFormatter::error(['message' => 'Wrong old password'], 'Validation Error!', 422);
            }
            if(!Hash::check($newPassword, $user->password)){
                return ResponseFormatter::error(['message' => 'New Password cannot same as current password'], 'Validation Error!', 422);
            }
            if($confirmPassword != $newPassword){
                return ResponseFormatter::error(['message' => 'Rewrite password not same as new password'], 'Validation Error!', 422);
            }
        }

        User::whereId($user->id)->update([
            'password' => Hash::make($newPassword)
        ]);

        return ResponseFormatter::success(['message' => 'Password successfully changed'], 'Your password has change');

    }
}
