<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use App\Mail\VerificationMail;
use App\Models\passwordReset;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'first_name'        =>  'required|string|min:3|max:50',
            'last_name'         =>  'required|string|min:3|max:50',
            'username'          =>  'required|string|min:3|max:50|unique:users,username',
            'email'             =>  'required|email|unique:users,email',
            'mobile'            =>  'nullable',
            'city'              =>  'nullable|string',
            'role'              =>  'required|in:A,C,E',
            'password'          =>  'required|confirmed'
        ]);


        // Generate a verification token
        $token = Str::random(64);

        $user = User::create($request->only(['first_name', 'last_name', 'username', 'email', 'mobile', 'city', 'role'])
            + [
                'password'              =>  Hash::make($request->password),
                'email_verify_token'    =>  $token,
            ]);

        // Send verification email to the user
        Mail::to($user->email)->send(new VerificationMail($user, $token));

        return ok(__('strings.auth.register'));
    }

    public function verifyAccount(Request $request)
    {
        $request->validate([
            'email'  => 'required|email',
            'token'  => 'required'
        ]);

        // Get User based on email and verification token
        $user = User::where('email', $request->email)->where('email_verify_token', $request->token)->first();

        if (empty($user)) {
            // Return error if user is not found based on email and token
            return error(__('strings.auth.email_token_invalid'), [], 'validation');
        }

        if (!empty($user->email_verified_at)) {
            // Return error if the user is already verified
            return error(__('strings.auth.already_verified'), [], 'validation');
        } else {
            $user->update([
                'email_verify_token'    =>  null,
                'email_verified_at'     => Carbon::now(),
                'is_active'             =>  true,
            ]);
            return ok(__('strings.auth.verified'));
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    =>  'required|email|exists:users,email',
            'password' =>  'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (empty($user->email_verified_at)) {
            return error(__('strings.auth.verify_email'), [], 'validation'); // company
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_active' => true])) {
            // $user   =   auth()->user();

            $token = $user->createToken('api_token')->plainTextToken;
            return ok(__('strings.auth.login'), [
                'user' => $user,
                'token' => $token
            ]);
        }
        return error(__('strings.auth.invalid_credential'));
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' =>  'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if the user's account has been deleted
        if ($user && $user->deleted_at != null) {
            return error(__('strings.auth.account_delete'), [], 'validation');
        }

        $data  = passwordReset::updateOrCreate(
            ['email'    =>  $request->email],
            [
                'email'         =>  $request->email,
                'token'         =>  Str::random(64),
                'expired_at'    =>  now()->addDays(2),
            ]
        );

        $user['token'] = $data->token;

        Mail::to($user->email)->send(new ForgotPasswordMail($user));
        return ok(__('strings.auth.reset_link_sent'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 =>  'required|email',
            'token'                 =>  'required|exists:password_resets,token',
            'password'              =>  'required|min:8|max:12',
            'password_confirmation' =>  'required|same:password',
        ]);

        $passwordReset = PasswordReset::where('token', $request->token)->where('email', $request->email)->first();

        if (!$passwordReset) {
            return error(__('strings.auth.reset_token_notfound'), [], 'validation');
        }

        if ($passwordReset->expired_at >= now()) {
            // User::updateOrCreate(
            //     ['email' => $passwordReset->email],
            //     [
            //         'password' => Hash::make($request->password),
            //     ]
            // );
            $user = User::where('email', $request->email)->first();

            $user->update(['password' =>  Hash::make($request->password)]);

            $passwordReset->delete();

            return ok(__('strings.auth.reset_password'));
        }
        return error(__('strings.auth.link_expired'));
    }
}
