<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(Request $request): View
    {
        $query = Coupon::latest();

        if ($search = $request->input('search')) {
            $query->where('code', 'like', "%{$search}%");
        }

        if ($request->input('status') === 'active') {
            $query->where('is_active', true);
        } elseif ($request->input('status') === 'inactive') {
            $query->where('is_active', false);
        }

        $coupons = $query->paginate(25)->withQueryString();

        $stats = [
            'total'    => Coupon::count(),
            'active'   => Coupon::where('is_active', true)->count(),
            'expired'  => Coupon::where('expires_at', '<', now())->count(),
            'used_up'  => Coupon::whereColumn('times_used', '>=', 'usage_limit')
                                ->whereNotNull('usage_limit')->count(),
        ];

        return view('other.coupons-list', compact('coupons', 'stats'));
    }

    public function create(): View
    {
        return view('other.coupons-add');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code'          => ['required', 'string', 'max:64', 'unique:coupons,code'],
            'type'          => ['required', 'in:fixed,percent'],
            'value'         => ['required', 'numeric', 'min:0.01', 'max:100' . ($request->input('type') === 'fixed' ? '000' : '')],
            'minimum_order' => ['nullable', 'numeric', 'min:0'],
            'usage_limit'   => ['nullable', 'integer', 'min:1'],
            'expires_at'    => ['nullable', 'date', 'after:today'],
            'is_active'     => ['nullable', 'boolean'],
        ]);

        if ($data['type'] === 'percent' && $data['value'] > 100) {
            return back()->withErrors(['value' => 'Percentage discount cannot exceed 100%.'])->withInput();
        }

        $data['code']      = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active', true);

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', "Coupon {$data['code']} created successfully.");
    }

    public function edit(Coupon $coupon): View
    {
        return view('other.coupons-edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $data = $request->validate([
            'code'          => ['required', 'string', 'max:64', "unique:coupons,code,{$coupon->id}"],
            'type'          => ['required', 'in:fixed,percent'],
            'value'         => ['required', 'numeric', 'min:0.01'],
            'minimum_order' => ['nullable', 'numeric', 'min:0'],
            'usage_limit'   => ['nullable', 'integer', 'min:1'],
            'expires_at'    => ['nullable', 'date'],
            'is_active'     => ['nullable', 'boolean'],
        ]);

        if ($data['type'] === 'percent' && $data['value'] > 100) {
            return back()->withErrors(['value' => 'Percentage discount cannot exceed 100%.'])->withInput();
        }

        $data['code']      = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active');

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', "Coupon {$coupon->code} updated.");
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $code = $coupon->code;
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', "Coupon {$code} deleted.");
    }

    public function toggleActive(Coupon $coupon): RedirectResponse
    {
        $coupon->update(['is_active' => !$coupon->is_active]);
        $state = $coupon->is_active ? 'enabled' : 'disabled';

        return back()->with('success', "Coupon {$coupon->code} {$state}.");
    }
}
