<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ChatSettingController;
use App\Http\Controllers\Api\AiChatController;
use App\Http\Controllers\Api\Admin\GroupAdminController;
use App\Http\Controllers\Api\Admin\AdminController;


/*
=========================================
PUBLIC
=========================================
*/

Route::post(
    'register',
    [AuthController::class, 'register']
);

Route::post(
    'login',
    [AuthController::class, 'login']
);

Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('resend-otp', [AuthController::class, 'resendOtp']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);



    Route::post(
    '/save-fcm-token',
    [AuthController::class, 'saveFcmToken']
)->middleware('auth:sanctum');



/*
=========================================
PROTECTED
=========================================
*/

Route::middleware('auth:sanctum')
    ->group(function () {





        /*
        profile
        */

        Route::get(
            'profile',
            [AuthController::class, 'profile']
        );


        Route::post('change-password', [AuthController::class, 'changePassword']);

        /*
        update profile
        */

        Route::post(
            'update-profile',
            [AuthController::class, 'updateProfile']
        );

        /*
        logout
        */

        Route::post(
            'logout',
            [AuthController::class, 'logout']
        );


            /*
    =========================================
    PRIVATE CHAT
    =========================================
    */

    Route::get(
        'users',
        [MessageController::class, 'users']
    );

    Route::post(
        'send-message',
        [MessageController::class, 'send']
    );

    Route::get(
        'conversation/{userId}',
        [MessageController::class, 'conversation']
    );

    Route::get(
        'conversations',
        [MessageController::class, 'conversations']
    );

    Route::delete(
        'delete-message/{id}',
        [MessageController::class, 'delete']
    );

    Route::post(
    'typing',
    [MessageController::class, 'typing']
);

Route::post(
    'react-message',
    [MessageController::class, 'react']
);

Route::delete(
    'delete-for-everyone/{id}',
    [MessageController::class, 'deleteForEveryone']
);

Route::post(
    'search-messages',
    [MessageController::class, 'search']
);


        /*
    =========================================
    GROUP CHAT
    =========================================
    */

    Route::post(
        'create-group',
        [GroupController::class, 'create']
    );

    Route::get(
        'my-groups',
        [GroupController::class, 'myGroups']
    );

    Route::get(
        'group-details/{groupId}',
        [GroupController::class, 'details']
    );

    Route::post(
        'group-send-message/{groupId}',
        [GroupController::class, 'sendMessage']
    );

    Route::get(
        'group-messages/{groupId}',
        [GroupController::class, 'messages']
    );

    Route::post(
        'group-add-member/{groupId}',
        [GroupController::class, 'addMember']
    );

    Route::delete(
        'group-remove-member/{groupId}/{userId}',
        [GroupController::class, 'removeMember']
    );

    Route::delete(
        'delete-group/{groupId}',
        [GroupController::class, 'delete']
    );


    /*
=========================================
CHAT SETTINGS
=========================================
*/

Route::post(
    'pin-chat/{userId}',
    [ChatSettingController::class, 'pin']
);

Route::post(
    'unpin-chat/{userId}',
    [ChatSettingController::class, 'unpin']
);

Route::post(
    'archive-chat/{userId}',
    [ChatSettingController::class, 'archive']
);

Route::post(
    'unarchive-chat/{userId}',
    [ChatSettingController::class, 'unarchive']
);

Route::post(
    'mute-chat/{userId}',
    [ChatSettingController::class, 'mute']
);

Route::post(
    'unmute-chat/{userId}',
    [ChatSettingController::class, 'unmute']
);

Route::post(
    'block-user/{userId}',
    [ChatSettingController::class, 'block']
);

Route::post(
    'unblock-user/{userId}',
    [ChatSettingController::class, 'unblock']
);


Route::post(
    'ai-chat',
    [AiChatController::class, 'ask']
);





    });

/*
=========================================
ADMIN ONLY
=========================================
*/

Route::middleware([
    'auth:sanctum',
    'role:admin'
])->prefix('admin')->group(function () {


    /*
    =========================================
    DASHBOARD
    =========================================
    */

    Route::get(
        '/dashboard',
        [AdminController::class, 'dashboard']
    );

    /*
    =========================================
    USERS
    =========================================
    */

    Route::get(
        '/users',
        [AdminController::class, 'users']
    );

    Route::put(
        '/users/{id}/ban',
        [AdminController::class, 'banUser']
    );

    Route::put(
        '/users/{id}/unban',
        [AdminController::class, 'unbanUser']
    );



    Route::delete(
        '/users/{id}',
        [AdminController::class, 'deleteUser']
    );



/*
=========================================
GROUPS
=========================================
*/

Route::get(
    '/groups',
    [GroupAdminController::class, 'groups']
);

Route::get(
    '/groups/{id}',
    [GroupAdminController::class, 'show']
);

Route::delete(
    '/groups/{id}',
    [GroupAdminController::class, 'deleteGroup']
);

Route::delete(
    '/groups/{groupId}/members/{userId}',
    [GroupAdminController::class, 'removeMember']
);

Route::put(
    '/groups/{groupId}/members/{userId}/make-admin',
    [GroupAdminController::class, 'makeAdmin']
);

Route::delete(
    '/groups/{groupId}/messages',
    [GroupAdminController::class, 'deleteMessages']
);

    
});