<?php 
// namespace App\Http\Controllers\Api;

// use App\Models\User;
// use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;

// class AuthController extends Controller
// {
//     /* ========================================= REGISTER ========================================= */
//     public function register(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'name'           => 'required|string|max:255',
//             'email'          => 'required|email|unique:users,email',
//             'password'       => 'required|min:6|confirmed',
//             'phone'          => 'nullable|string',
//             'bio'            => 'nullable|string',
//             'profile_image'  => 'nullable|image|max:2048',
//             'role'           => 'nullable|in:user,admin'
//         ]);

//         if ($validator->fails()) {
//             return response()->json([
//                 'status' => false,
//                 'errors' => $validator->errors()
//             ], 422);
//         }

//         $imagePath = null;
//         if ($request->hasFile('profile_image')) {
//             $imagePath = $request->file('profile_image')->store('profiles', 'public');
//         }

//         $user = User::create([
//             'name'          => $request->name,
//             'email'         => $request->email,
//             'password'      => Hash::make($request->password),
//             'phone'         => $request->phone,
//             'bio'           => $request->bio,
//             'profile_image' => $imagePath,
//             'role'          => $request->role ?? 'user'
//         ]);

//         $token = $user->createToken('auth_token')->plainTextToken;

//         return response()->json([
//             'status'  => true,
//             'message' => 'Registered Successfully',
//             'token'   => $token,
//             'user'    => $user,
//         ]);
//     }

//     /* ========================================= LOGIN ========================================= */
//     public function login(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'email'    => 'required|email',
//             'password' => 'required',
//         ]);

//         if ($validator->fails()) {
//             return response()->json([
//                 'status' => false,
//                 'errors' => $validator->errors()
//             ], 422);
//         }

//         $user = User::where('email', $request->email)->first();

//         if (!$user || !Hash::check($request->password, $user->password)) {
//             return response()->json([
//                 'status'  => false,
//                 'message' => 'Invalid Credentials'
//             ], 401);
//         }

//         $user->update([
//             'is_online' => true,
//         ]);

//         $token = $user->createToken('auth_token')->plainTextToken;

//         return response()->json([
//             'status' => true,
//             'token'  => $token,
//             'user'   => $user,
//         ]);
//     }

//     /* ========================================= LOGOUT ========================================= */
//     public function logout(Request $request)
//     {
//         $request->user()->currentAccessToken()->delete();

//         $request->user()->update([
//             'is_online' => false,
//             'last_seen' => now(),
//         ]);

//         return response()->json([
//             'status'  => true,
//             'message' => 'Logged out'
//         ]);
//     }

//     /* ========================================= PROFILE ========================================= */
//     public function profile(Request $request)
//     {
//         return response()->json([
//             'status' => true,
//             'user'   => $request->user(),
//         ]);
//     }

//     /* ========================================= UPDATE PROFILE ========================================= */
//     public function updateProfile(Request $request)
//     {
//         $user = auth()->user();

//         $validator = Validator::make($request->all(), [
//             'name'          => 'nullable|string|max:255',
//             'phone'         => 'nullable|string',
//             'bio'           => 'nullable|string',
//             'profile_image' => 'nullable|image|max:2048',
//         ]);

//         if ($validator->fails()) {
//             return response()->json([
//                 'status' => false,
//                 'errors' => $validator->errors()
//             ], 422);
//         }

//         if ($request->hasFile('profile_image')) {
//             $imagePath = $request->file('profile_image')->store('profiles', 'public');
//             $user->profile_image = $imagePath;
//         }

//         $user->update([
//             'name'  => $request->name ?? $user->name,
//             'phone' => $request->phone ?? $user->phone,
//             'bio'   => $request->bio ?? $user->bio,
//         ]);

//         $user->save();

//         return response()->json([
//             'status'  => true,
//             'message' => 'Profile Updated',
//             'user'    => $user,
//         ]);
//     }

//     /* ========================================= SAVE FCM TOKEN ========================================= */
//     public function saveFcmToken(Request $request)
//     {
//         $request->validate([
//             'fcm_token' => 'required|string',
//         ]);

//         auth()->user()->update([
//             'fcm_token' => $request->fcm_token,
//         ]);

//         return response()->json([
//             'status' => true,
//         ]);
//     }
// }



namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;

class AuthController extends Controller
{
    /* ========================================= REGISTER ========================================= */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|min:6|confirmed',
            'phone'          => 'required|string',
            'bio'            => 'nullable|string',
            'profile_image'  => 'nullable|image|max:2048',
            'role'           => 'nullable|in:user,admin'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
        }

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'phone'         => $request->phone,
            'bio'           => $request->bio,
            'profile_image' => $imagePath,
            'role'          => $request->role ?? 'user'
        ]);

        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
        $twilio->verify->v2->services(env('TWILIO_VERIFY_SERVICE'))
            ->verifications
            ->create($user->phone, "sms");

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Registered Successfully. Please verify OTP sent via SMS.',
            'token'   => $token,
            'user'    => $user,
        ]);
    }

    /* ========================================= VERIFY OTP ========================================= */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp'   => 'required|digits:6',
        ]);

        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
        $verification = $twilio->verify->v2->services(env('TWILIO_VERIFY_SERVICE'))
            ->verificationChecks
            ->create([
                'to'   => $request->phone,
                'code' => $request->otp,
            ]);

        if ($verification->status === "approved") {
            return response()->json(['status' => true, 'message' => 'OTP Verified']);
        }

        return response()->json(['status' => false, 'message' => 'Invalid or expired OTP'], 422);
    }

    /* ========================================= RESEND OTP ========================================= */
    public function resendOtp(Request $request)
    {
        $request->validate(['phone' => 'required|string']);
        $user = User::where('phone', $request->phone)->firstOrFail();

        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
        $twilio->verify->v2->services(env('TWILIO_VERIFY_SERVICE'))
            ->verifications
            ->create($user->phone, "sms");

        return response()->json(['status' => true, 'message' => 'OTP Resent via SMS']);
    }

    /* ========================================= LOGIN ========================================= */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => false, 'message' => 'Invalid Credentials'], 401);
        }

        $user->update(['is_online' => true]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['status' => true, 'token' => $token, 'user' => $user]);
    }

    /* ========================================= FORGOT PASSWORD ========================================= */
    public function forgotPassword(Request $request)
    {
        $request->validate(['phone' => 'required|string']);
        $user = User::where('phone', $request->phone)->firstOrFail();

        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
        $twilio->verify->v2->services(env('TWILIO_VERIFY_SERVICE'))
            ->verifications
            ->create($user->phone, "sms");

        return response()->json(['status' => true, 'message' => 'OTP sent via SMS']);
    }

    /* ========================================= RESET PASSWORD ========================================= */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string',
            'otp'      => 'required|digits:6',
            'password' => 'required|min:6|confirmed',
        ]);

        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
        $verification = $twilio->verify->v2->services(env('TWILIO_VERIFY_SERVICE'))
            ->verificationChecks
            ->create([
                'to'   => $request->phone,
                'code' => $request->otp,
            ]);

        if ($verification->status !== "approved") {
            return response()->json(['status' => false, 'message' => 'Invalid or expired OTP'], 422);
        }

        $user = User::where('phone', $request->phone)->firstOrFail();
        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['status' => true, 'message' => 'Password Reset Successfully']);
    }

    /* ========================================= CHANGE PASSWORD ========================================= */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['status' => false, 'message' => 'Current password incorrect'], 422);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['status' => true, 'message' => 'Password Changed Successfully']);
    }

    /* ========================================= LOGOUT ========================================= */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $request->user()->update(['is_online' => false, 'last_seen' => now()]);

        return response()->json(['status' => true, 'message' => 'Logged out']);
    }

    /* ========================================= PROFILE ========================================= */
    public function profile(Request $request)
    {
        return response()->json(['status' => true, 'user' => $request->user()]);
    }

    /* ========================================= UPDATE PROFILE ========================================= */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name'          => 'nullable|string|max:255',
            'phone'         => 'nullable|string',
            'bio'           => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $imagePath;
        }

        $user->update([
            'name'  => $request->name ?? $user->name,
            'phone' => $request->phone ?? $user->phone,
            'bio'   => $request->bio ?? $user->bio,
        ]);

        $user->save();

        return response()->json(['status' => true, 'message' => 'Profile Updated', 'user' => $user]);
    }

    /* ========================================= SAVE FCM TOKEN ========================================= */
    public function saveFcmToken(Request $request)
    {
        $request->validate(['fcm_token' => 'required|string']);
        auth()->user()->update(['fcm_token' => $request->fcm_token]);

        return response()->json(['status' => true]);
    }
}