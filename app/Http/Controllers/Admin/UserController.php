<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('created_at')->get();
        return view('pages.admin.users', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name'     => 'required|string|max:150',
            'email'         => 'nullable|email|unique:users,email',
            'phone_number'  => 'nullable|string|max:15|unique:users,phone_number',
            'role'          => 'required|in:admin,staff,customer',
            'password'      => 'required|string|min:6',
            'gender'        => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
        ], [
            'full_name.required' => 'Vui lòng nhập họ tên.',
            'role.required'      => 'Vui lòng chọn vai trò.',
            'password.required'  => 'Vui lòng nhập mật khẩu.',
        ]);

        $customerCode = 'CUS' . now()->format('ymdHis') . rand(10, 99);

        User::create([
            'customer_code' => $customerCode,
            'full_name'     => $request->full_name,
            'email'         => $request->email,
            'phone_number'  => $request->phone_number,
            'password'      => Hash::make($request->password),
            'role'          => $request->role,
            'gender'        => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'status'        => 'active',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Tạo tài khoản mới thành công.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'full_name'     => 'required|string|max:150',
            'email'         => 'nullable|email|unique:users,email,' . $id,
            'phone_number'  => 'nullable|string|max:15|unique:users,phone_number,' . $id,
            'role'          => 'required|in:admin,staff,customer',
            'status'        => 'required|in:active,inactive,blocked',
            'password'      => 'nullable|string|min:6',
            'gender'        => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
        ]);

        $data = [
            'full_name'     => $request->full_name,
            'email'         => $request->email,
            'phone_number'  => $request->phone_number,
            'role'          => $request->role,
            'status'        => $request->status,
            'gender'        => $request->gender,
            'date_of_birth' => $request->date_of_birth,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật tài khoản thành công.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Đã xóa tài khoản thành công.');
    }
}
