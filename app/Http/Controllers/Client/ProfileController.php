<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\ShoppingCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Throwable;

class ProfileController extends Controller
{
    public function home(): View
    {
        $user = Auth::user();
        $addresses = $user->addresses()
            ->orderByDesc('is_default')
            ->orderByDesc('updated_at')
            ->get();

        $districts = $this->getDistrictsFromApi();
        $accountStatus = $user->status;

        $activeCart = ShoppingCart::with('items')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();
        $cartCount = $activeCart ? (int) $activeCart->items->sum('quantity') : 0;

        $placedCount = Order::where('user_id', $user->id)
            ->whereIn('fulfillment_status', ['pending', 'processing'])
            ->count();

        $shippingCount = Order::where('user_id', $user->id)
            ->where('fulfillment_status', 'shipped')
            ->count();

        $deliveredCount = Order::where('user_id', $user->id)
            ->where('fulfillment_status', 'delivered')
            ->count();

        return view('pages.client.profile.home', compact(
            'addresses',
            'districts',
            'accountStatus',
            'cartCount',
            'placedCount',
            'shippingCount',
            'deliveredCount'
        ));
    }

    public function update(Request $request): RedirectResponse
    {
        try {
            $user = Auth::user();
            $validated = $request->validate([
                'full_name' => ['required', 'min:3'],
                'email' => ['nullable', 'email', 'unique:users,email,' . $user->id],
                'phone_number' => ['nullable', 'unique:users,phone_number,' . $user->id],
                'date_of_birth' => ['nullable', 'date'],
                'gender' => ['nullable', 'in:male,female,other'],
            ]);
            $user->fill($validated);
            $user->save();
            return back()->with('success', 'Cập nhật hồ sơ thành công.');
        } catch (Throwable $e) {
            dd('Lỗi update:', $e->getMessage());
        }
    }

    public function addresses(): View
    {
        $user = Auth::user();
        $addresses = $user->addresses()
            ->orderByDesc('is_default')
            ->orderByDesc('updated_at')
            ->get();
        $districts = $this->getDistrictsFromApi();
        return view('pages.client.profile.addresses', compact('addresses', 'districts'));
    }

    public function storeAddress(Request $request): RedirectResponse
    {
        try {
            $user = Auth::user();

            $data = $request->validate([
                'label' => ['nullable', 'string', 'max:100'],
                'receiver_name' => ['required', 'string', 'max:150'],
                'receiver_phone' => ['required', 'string', 'max:15'],
                'receiver_email' => ['nullable', 'email', 'max:150'],
                'ward_code' => ['required', 'string'],
                'address_line' => ['required', 'string', 'max:255'],
                'note' => ['nullable', 'string', 'max:255'],
                'is_default' => ['sometimes', 'boolean'],
            ]);

            $res = Http::withoutVerifying()->timeout(10)->get('https://provinces.open-api.vn/api/v2/p/79?depth=2');
            if ($res->failed()) {
                dd('API lỗi khi lấy danh sách phường/xã:', $res->status(), $res->body());
            }

            $dataApi = $res->json();
            $wardsList = $dataApi['wards'] ?? [];
            $ward = collect($wardsList)->firstWhere('code', $data['ward_code']);

            if (!$ward) {
                dd('Không tìm thấy phường/xã có mã:', $data['ward_code']);
            }

            $data['user_id'] = $user->id;
            $data['district_name'] = 'Thành phố Hồ Chí Minh';
            $data['ward_name'] = $ward['name'] ?? 'Không rõ';
            $data['district_code'] = '79';
            $data['is_default'] = $request->boolean('is_default');

            $address = CustomerAddress::create($data);

            if ($address->is_default) {
                CustomerAddress::where('user_id', $user->id)
                    ->where('id', '<>', $address->id)
                    ->update(['is_default' => false]);
            }

            return back()->with('success', 'Đã thêm địa chỉ giao hàng mới.');
        } catch (Throwable $e) {
            dd('Lỗi khi lưu địa chỉ:', $e->getMessage(), $e->getTraceAsString());
        }
    }

    public function deleteAddress(CustomerAddress $address): RedirectResponse
    {
        try {
            $user = Auth::user();
            abort_unless($address->user_id === $user->id, 403);

            $address->delete();

            if ($address->is_default) {
                CustomerAddress::where('user_id', $user->id)
                    ->orderByDesc('updated_at')
                    ->first()?->update(['is_default' => true]);
            }

            return back()->with('success', 'Đã xóa địa chỉ thành công.');
        } catch (Throwable $e) {
            dd('Lỗi xóa địa chỉ:', $e->getMessage());
        }
    }

    public function wards(string $districtCode)
    {
        try {
            $res = Http::withoutVerifying()->timeout(10)->get("https://provinces.open-api.vn/api/v2/d/{$districtCode}?depth=2");
            if ($res->failed()) {
                return response()->json(['error' => 'API request failed'], 500);
            }
            $data = $res->json();
            $wards = collect($data['wards'] ?? [])->map(fn($w) => [
                'code' => (string) $w['code'],
                'name' => trim($w['name']),
            ])->values();
            return response()->json([
                'district' => $districtCode,
                'wards' => $wards,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'wards' => [],
            ], 500);
        }
    }

    public function getHcmWards()
    {
        try {
            $res = Http::withoutVerifying()->timeout(10)->get('https://provinces.open-api.vn/api/v2/p/79?depth=2');
            if ($res->failed()) {
                dd('API getHcmWards lỗi:', $res->status(), $res->body());
            }
            $data = $res->json();
            $wards = collect($data['wards'] ?? [])->map(fn($w) => [
                'code' => (string) $w['code'],
                'name' => trim($w['name']),
            ])->values();
            return response()->json(['wards' => $wards]);
        } catch (Throwable $e) {
            dd('Lỗi getHcmWards:', $e->getMessage());
        }
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:6', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng.')->withInput();
        }

        try {
            $user->update(['password' => Hash::make($request->new_password)]);
            return back()->with('success', 'Đổi mật khẩu thành công!');
        } catch (Throwable $e) {
            dd('Lỗi đổi mật khẩu:', $e->getMessage());
        }
    }

    private function getDistrictsFromApi(): array
    {
        try {
            $res = Http::withoutVerifying()->timeout(10)->get('https://provinces.open-api.vn/api/v2/p/79?depth=2');
            $data = $res->json();
            $districts = [];
            foreach ($data['districts'] ?? [] as $d) {
                $districts[] = ['code' => (string) $d['code'], 'name' => trim($d['name'])];
            }
            usort($districts, fn($a, $b) => strcmp($a['name'], $b['name']));
            return $districts;
        } catch (Throwable $e) {
            dd('Lỗi getDistrictsFromApi:', $e->getMessage());
        }
    }

    private function getWardsFromApi(string $districtCode): array
    {
        try {
            $res = Http::withoutVerifying()->timeout(10)->get("https://provinces.open-api.vn/api/v2/d/{$districtCode}?depth=2");
            $data = $res->json();
            $wards = [];
            foreach ($data['wards'] ?? [] as $w) {
                $wards[] = ['code' => (string) $w['code'], 'name' => trim($w['name'])];
            }
            usort($wards, fn($a, $b) => strcmp($a['name'], $b['name']));
            return $wards;
        } catch (Throwable $e) {
            dd('Lỗi getWardsFromApi:', $e->getMessage());
        }
    }

    private function getDistrictsAndWardsMap(): array
    {
        try {
            $res = Http::withoutVerifying()->timeout(10)->get('https://provinces.open-api.vn/api/v2/p/79?depth=2');
            $data = $res->json();
            $districts = [];
            $wardMap = [];
            foreach ($data['districts'] ?? [] as $d) {
                $districtCode = (string) $d['code'];
                $districts[] = ['code' => $districtCode, 'name' => trim($d['name'])];
                $wards = [];
                foreach ($d['wards'] ?? [] as $w) {
                    $wards[] = ['code' => (string) $w['code'], 'name' => trim($w['name'])];
                }
                $wardMap[$districtCode] = $wards;
            }
            return [$districts, $wardMap];
        } catch (Throwable $e) {
            dd('Lỗi getDistrictsAndWardsMap:', $e->getMessage());
        }
    }
}
