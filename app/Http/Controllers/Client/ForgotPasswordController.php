<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('pages.client.auth.forgot_password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Không tìm thấy tài khoản với email này.');
        }

        // Xoá OTP cũ
        OtpToken::where('email', $request->email)
            ->where('purpose', 'password_reset')
            ->delete();

        $otp = random_int(100000, 999999);
        $expires = now()->addMinutes(5);

        OtpToken::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'otp_code' => $otp,
            'purpose' => 'password_reset',
            'expires_at' => $expires,
            'is_used' => false,
            'delivered_via' => 'email',
        ]);

        Mail::raw("Mã xác nhận đặt lại mật khẩu của bạn là: {$otp} (hiệu lực 60 giây).", function ($message) use ($user) {
            $message->to($user->email)->subject('Mã xác nhận - Kim Loan Cake');
        });

        session(['reset_email' => $user->email]);
        return back()->with('status', 'Đã gửi mã xác nhận đến email của bạn!');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|array|size:6']);

        $email = session('reset_email');
        $inputOtp = implode('', $request->otp);

        $token = OtpToken::where('email', $email)
            ->where('purpose', 'password_reset')
            ->where('is_used', false)
            ->latest()
            ->first();

        if (!$token) {
            return back()->with('error', 'Không tìm thấy mã hoặc phiên đã hết hạn.');
        }

        if (now()->greaterThan($token->expires_at)) {
            return back()->with('error', 'Mã xác nhận đã hết hạn.');
        }

        if ($token->otp_code != $inputOtp) {
            $token->increment('attempts');
            return back()->with('error', 'Mã xác nhận không đúng.');
        }

        $token->update(['is_used' => true, 'consumed_at' => now()]);

        $user = User::where('email', $email)->first();
        $newPassword = Str::random(8);
        $user->update(['password' => Hash::make($newPassword)]);

        Mail::raw("Mật khẩu mới của bạn là: {$newPassword}", function ($message) use ($email) {
            $message->to($email)->subject('Mật khẩu mới - Kim Loan Cake');
        });

        session()->forget('reset_email');
        return redirect()->route('login')->with('success', 'Mật khẩu mới đã được gửi đến email của bạn.');
    }
}
