<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class CurrencyController extends Controller
{
    public function __construct(private readonly CurrencyService $currencies) {}

    public function index(): View
    {
        $currencies = Currency::orderByDesc('is_default')->orderBy('code')->get();

        return view('general.currencies.index', compact('currencies'));
    }

    /**
     * Update the exchange rate for a single currency.
     * Called by the inline form on the index page.
     */
    public function updateRate(Request $request, Currency $currency): RedirectResponse
    {
        $request->validate([
            'rate' => ['required', 'numeric', 'min:0.000001', 'max:99999'],
        ]);

        if ($currency->is_default) {
            return back()->with('error', "The default currency rate must stay at 1.000000 (it is the base).");
        }

        $updated = $this->currencies->updateRate($currency->code, (float) $request->input('rate'));

        if (! $updated) {
            return back()->with('error', "Failed to update rate for {$currency->code}.");
        }

        return back()->with('success', "Rate for {$currency->code} updated to {$request->input('rate')}.");
    }

    /**
     * Add a new currency.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code'               => ['required', 'string', 'size:3', 'uppercase', 'unique:currencies,code'],
            'name'               => ['required', 'string', 'max:100'],
            'rate'               => ['required', 'numeric', 'min:0.000001', 'max:99999'],
            'region_codes'       => ['nullable', 'string'],
        ]);

        $regionCodes = null;
        if ($request->filled('region_codes')) {
            $regionCodes = collect(preg_split('/[\s,]+/', strtoupper(trim($request->input('region_codes')))))
                ->filter()
                ->unique()
                ->values()
                ->all();
        }

        Currency::create([
            'code'         => strtoupper($request->input('code')),
            'name'         => $request->input('name'),
            'rate'         => (float) $request->input('rate'),
            'is_default'   => false,
            'region_codes' => $regionCodes,
        ]);

        Cache::forget('fado_currencies');

        return back()->with('success', "Currency {$request->input('code')} added.");
    }

    /**
     * Delete a non-default currency.
     */
    public function destroy(Currency $currency): RedirectResponse
    {
        if ($currency->is_default) {
            return back()->with('error', 'Cannot delete the default currency.');
        }

        $currency->delete();
        Cache::forget('fado_currencies');

        return back()->with('success', "Currency {$currency->code} deleted.");
    }
}
