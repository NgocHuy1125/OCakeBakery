<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('pages.client.auth.loginPage');
    }

    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'status' => 'active',
        ];

        try {
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();
                $user = Auth::user();
                session(['fullname' => $user->full_name, 'role' => $user->role]);
                $user->update(['last_login_at' => now()]);

                return redirect()->intended('/')
                    ->with('success', 'Đăng nhập thành công!');
            }

            return back()->with('error', 'Email hoặc mật khẩu không đúng, hoặc tài khoản đã bị vô hiệu hóa.')
                         ->onlyInput('email');
        } catch (Throwable $e) {
            return back()->with('error', 'Đã xảy ra lỗi, vui lòng thử lại.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Đăng xuất thành công!');
    }

    public function showRegisterForm()
    {
        return view('pages.client.auth.registerPage');
    }

    public function processRegister(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone_number' => ['required', 'regex:/^(0|\+84)[0-9]{9,10}$/', 'unique:users,phone_number'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        try {
            $normalizedPhone = $validated['phone_number'];
            if (str_starts_with($normalizedPhone, '+84')) {
                $normalizedPhone = '0' . substr($normalizedPhone, 3);
            }

            $user = User::create([
                'customer_code' => Str::upper(Str::random(8)),
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone_number' => $normalizedPhone,
                'password' => Hash::make($validated['password']),
                'role' => 'customer',
                'status' => 'active',
            ]);

            Auth::login($user);
            $request->session()->regenerate();
            session(['fullname' => $user->full_name, 'role' => $user->role]);

            return redirect()->intended('/')
                ->with('success', 'Đăng ký thành công!');
        } catch (Throwable $e) {
            return back()->with('error', 'Không thể tạo tài khoản, vui lòng thử lại.')->withInput();
        }
    }
}
