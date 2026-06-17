<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CurrencyService
{
    private const SESSION_KEY = 'fado_currency';
    private const CACHE_TTL   = 3600; // 1 hour

    /**
     * Return the active Currency for the current request.
     * Priority: explicit session selection → GeoIP detection → default (EUR).
     */
    public function current(): Currency
    {
        $code = Session::get(self::SESSION_KEY)
            ?? $this->detectFromGeoIp()
            ?? $this->default()->code;

        return $this->findByCode($code) ?? $this->default();
    }

    /**
     * Explicitly set the user's preferred currency for this session.
     */
    public function setForSession(string $code): bool
    {
        $currency = $this->findByCode(strtoupper($code));

        if (! $currency) {
            return false;
        }

        Session::put(self::SESSION_KEY, $currency->code);

        return true;
    }

    /**
     * Convert a EUR amount to the active currency.
     */
    public function convert(float $amountEur, ?Currency $currency = null): float
    {
        $currency ??= $this->current();

        return round($amountEur * (float) $currency->rate, 2);
    }

    /**
     * Format a EUR amount as a localised price string.
     * e.g. €1,250.00  or  $1,375.00
     */
    public function format(float $amountEur, ?Currency $currency = null): string
    {
        $currency ??= $this->current();
        $converted = $this->convert($amountEur, $currency);

        return match ($currency->code) {
            'EUR'   => '€' . number_format($converted, 2),
            'USD'   => '$' . number_format($converted, 2),
            default => $currency->code . ' ' . number_format($converted, 2),
        };
    }

    /**
     * Return all available currencies (cached).
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Currency>
     */
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('fado_currencies', self::CACHE_TTL, fn () => Currency::all());
    }

    /**
     * Update the exchange rate for a currency and bust the cache.
     */
    public function updateRate(string $code, float $rate): bool
    {
        $currency = $this->findByCode($code);

        if (! $currency || $rate <= 0) {
            return false;
        }

        $currency->update(['rate' => $rate]);
        Cache::forget('fado_currencies');

        return true;
    }

    // -------------------------------------------------------------------------

    private function default(): Currency
    {
        return Cache::remember('fado_currency_default', self::CACHE_TTL, fn () =>
            Currency::where('is_default', true)->firstOrFail()
        );
    }

    private function findByCode(string $code): ?Currency
    {
        return $this->all()->firstWhere('code', $code);
    }

    /**
     * Resolve the visitor's ISO 3166-1 alpha-2 country code to a currency.
     * Uses ip-api.com (free tier, no key required, 45 req/min limit).
     * Returns null on any failure so the caller can fall back to default.
     */
    private function detectFromGeoIp(): ?string
    {
        $ip = request()->ip();

        // Skip detection for local/private IPs
        if ($this->isPrivateIp($ip)) {
            return null;
        }

        $countryCode = Cache::remember("fado_geoip_{$ip}", self::CACHE_TTL, function () use ($ip) {
            try {
                $response = Http::timeout(2)->get("http://ip-api.com/json/{$ip}?fields=countryCode");

                if ($response->successful()) {
                    return $response->json('countryCode');
                }
            } catch (\Throwable) {
                // GeoIP is best-effort — silently fall through
            }

            return null;
        });

        if (! $countryCode) {
            return null;
        }

        // Find first currency whose region_codes array contains this country
        $match = $this->all()->first(function (Currency $currency) use ($countryCode) {
            return is_array($currency->region_codes)
                && in_array($countryCode, $currency->region_codes, strict: true);
        });

        return $match?->code;
    }

    private function isPrivateIp(string $ip): bool
    {
        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === false;
    }
}
