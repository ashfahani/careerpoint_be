<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterCommittee;
use App\Http\Controllers\MasterCompetition;
use App\Http\Controllers\MasterInternship;
use App\Http\Controllers\MasterOrganization;
use App\Http\Controllers\MasterPublication;
use App\Http\Controllers\MasterSeminar;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/unauthorized', function () {
    return response()->json([
        'error' => [
            "message" => 'not auhorized!'
        ]
    ], 401);
})->name('unauthorized');

// Public routes of authtication
Route::controller(AuthController::class)->group(function() {
    Route::post('/login', 'login');
    Route::post('/reset-password', 'passwordReset')->name('password.reset');
    Route::post('/change-password', 'changePassword')->name('password.change')->middleware('auth:sanctum');
    Route::post('/forget-password', 'forgetPassword');
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'account'], function () {
    Route::get('/current', [AuthController::class, 'currentAccount']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'master'], function () {
    // Master Users
    Route::post('user/get-user', [UserController::class, 'getUsers']);
    Route::post('user/get-user/{id}', [UserController::class, 'getUsers']);   
    Route::get('user/get-user-detail/{id}', [UserController::class, 'getUserDetails']);    
    Route::post('user/add', [UserController::class, 'register']); 
    Route::post('user/update', [UserController::class, 'updateUser']); 
    Route::post('user/delete/{id}', [UserController::class, 'deleteUser']);
    
    // Master Committee
    Route::post('committee/get-level', [MasterCommittee::class, 'getLevel']);
    Route::post('committee/get-level/{id}', [MasterCommittee::class, 'getLevel']);   
    Route::get('committee/get-level-detail/{id}', [MasterCommittee::class, 'getLevelDetails']);    
    Route::post('committee/add-level', [MasterCommittee::class, 'addLevel']); 
    Route::post('committee/update-level', [MasterCommittee::class, 'updateLevel']); 
    Route::post('committee/delete-level/{id}', [MasterCommittee::class, 'deleteLevel']);
    Route::post('committee/get-role', [MasterCommittee::class, 'getRole']);
    Route::post('committee/get-role/{id}', [MasterCommittee::class, 'getRole']);   
    Route::get('committee/get-role-detail/{id}', [MasterCommittee::class, 'getRoleDetails']);    
    Route::post('committee/add-role', [MasterCommittee::class, 'addRole']); 
    Route::post('committee/update-role', [MasterCommittee::class, 'updateRole']); 
    Route::post('committee/delete-role/{id}', [MasterCommittee::class, 'deleteRole']);

    // Master Competition
    Route::post('competition/get-level', [MasterCompetition::class, 'getLevel']);
    Route::post('competition/get-level/{id}', [MasterCompetition::class, 'getLevel']);   
    Route::get('competition/get-level-detail/{id}', [MasterCompetition::class, 'getLevelDetails']);    
    Route::post('competition/add-level', [MasterCompetition::class, 'addLevel']); 
    Route::post('competition/update-level', [MasterCompetition::class, 'updateLevel']); 
    Route::post('competition/delete-level/{id}', [MasterCompetition::class, 'deleteLevel']);
    Route::post('competition/get-role', [MasterCompetition::class, 'getRole']);
    Route::post('competition/get-role/{id}', [MasterCompetition::class, 'getRole']);   
    Route::get('competition/get-role-detail/{id}', [MasterCompetition::class, 'getRoleDetails']);    
    Route::post('competition/add-role', [MasterCompetition::class, 'addRole']); 
    Route::post('competition/update-role', [MasterCompetition::class, 'updateRole']); 
    Route::post('competition/delete-role/{id}', [MasterCompetition::class, 'deleteRole']);

    // Master Internship
    Route::post('internship/get-level', [MasterInternship::class, 'getLevel']);
    Route::post('internship/get-level/{id}', [MasterInternship::class, 'getLevel']);   
    Route::get('internship/get-level-detail/{id}', [MasterInternship::class, 'getLevelDetails']);    
    Route::post('internship/add-level', [MasterInternship::class, 'addLevel']); 
    Route::post('internship/update-level', [MasterInternship::class, 'updateLevel']); 
    Route::post('internship/delete-level/{id}', [MasterInternship::class, 'deleteLevel']);
    Route::post('internship/get-role', [MasterInternship::class, 'getRole']);
    Route::post('internship/get-role/{id}', [MasterInternship::class, 'getRole']);   
    Route::get('internship/get-role-detail/{id}', [MasterInternship::class, 'getRoleDetails']);    
    Route::post('internship/add-role', [MasterInternship::class, 'addRole']); 
    Route::post('internship/update-role', [MasterInternship::class, 'updateRole']); 
    Route::post('internship/delete-role/{id}', [MasterInternship::class, 'deleteRole']);

    // Master Organization
    Route::post('organization/get-level', [MasterOrganization::class, 'getLevel']);
    Route::post('organization/get-level/{id}', [MasterOrganization::class, 'getLevel']);   
    Route::get('organization/get-level-detail/{id}', [MasterOrganization::class, 'getLevelDetails']);    
    Route::post('organization/add-level', [MasterOrganization::class, 'addLevel']); 
    Route::post('organization/update-level', [MasterOrganization::class, 'updateLevel']); 
    Route::post('organization/delete-level/{id}', [MasterOrganization::class, 'deleteLevel']);
    Route::post('organization/get-role', [MasterOrganization::class, 'getRole']);
    Route::post('organization/get-role/{id}', [MasterOrganization::class, 'getRole']);   
    Route::get('organization/get-role-detail/{id}', [MasterOrganization::class, 'getRoleDetails']);    
    Route::post('organization/add-role', [MasterOrganization::class, 'addRole']); 
    Route::post('organization/update-role', [MasterOrganization::class, 'updateRole']); 
    Route::post('organization/delete-role/{id}', [MasterOrganization::class, 'deleteRole']);

    // Master Publication
    Route::post('publication/get-level', [MasterPublication::class, 'getLevel']);
    Route::post('publication/get-level/{id}', [MasterPublication::class, 'getLevel']);   
    Route::get('publication/get-level-detail/{id}', [MasterPublication::class, 'getLevelDetails']);    
    Route::post('publication/add-level', [MasterPublication::class, 'addLevel']); 
    Route::post('publication/update-level', [MasterPublication::class, 'updateLevel']); 
    Route::post('publication/delete-level/{id}', [MasterPublication::class, 'deleteLevel']);
    Route::post('publication/get-role', [MasterPublication::class, 'getRole']);
    Route::post('publication/get-role/{id}', [MasterPublication::class, 'getRole']);   
    Route::get('publication/get-role-detail/{id}', [MasterPublication::class, 'getRoleDetails']);    
    Route::post('publication/add-role', [MasterPublication::class, 'addRole']); 
    Route::post('publication/update-role', [MasterPublication::class, 'updateRole']); 
    Route::post('publication/delete-role/{id}', [MasterPublication::class, 'deleteRole']);

    // Master Seminar
    Route::post('seminar/get-level', [MasterSeminar::class, 'getLevel']);
    Route::post('seminar/get-level/{id}', [MasterSeminar::class, 'getLevel']);   
    Route::get('seminar/get-level-detail/{id}', [MasterSeminar::class, 'getLevelDetails']);    
    Route::post('seminar/add-level', [MasterSeminar::class, 'addLevel']); 
    Route::post('seminar/update-level', [MasterSeminar::class, 'updateLevel']); 
    Route::post('seminar/delete-level/{id}', [MasterSeminar::class, 'deleteLevel']);
    Route::post('seminar/get-role', [MasterSeminar::class, 'getRole']);
    Route::post('seminar/get-role/{id}', [MasterSeminar::class, 'getRole']);   
    Route::get('seminar/get-role-detail/{id}', [MasterSeminar::class, 'getRoleDetails']);    
    Route::post('seminar/add-role', [MasterSeminar::class, 'addRole']); 
    Route::post('seminar/update-role', [MasterSeminar::class, 'updateRole']); 
    Route::post('seminar/delete-role/{id}', [MasterSeminar::class, 'deleteRole']);
});