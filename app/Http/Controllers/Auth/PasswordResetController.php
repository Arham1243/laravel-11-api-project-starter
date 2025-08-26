<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $token,
                'created_at' => now(),
            ]
        );

        $data = [
            'name' => $user->name,
            'verify_link' => config('app.frontend_url').'/auth/password/reset?token='.$token,
            'logo' => asset('assets/images/logo.png'),
        ];

        try {
            $subject = 'Password Reset - '.config('app.name');
            Mail::send('emails.reset-password', ['data' => $data], function ($message) use ($email, $subject) {
                $message->from(config('app.mail_from_address'));
                $message->to($email)->subject($subject);
            });
        } catch (\Throwable $e) {
            \Log::error('Password reset email failed for user '.$user->id.': '.$e->getMessage());
        }

        return response()->json([
            'status' => true,
            'message' => 'Password reset link sent to your email.',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data = $request->only('token', 'password');

        $record = DB::table('password_reset_tokens')
            ->where('token', $data['token'])
            ->first();

        if (! $record) {
            return response()->json(['message' => 'Invalid token.'], 400);
        }

        User::where('email', $record->email)->update([
            'password' => Hash::make($data['password']),
        ]);

        DB::table('password_reset_tokens')->where('email', $record->email)->delete();

        return response()->json(['status' => true, 'message' => 'Password reset successful.']);
    }
}
