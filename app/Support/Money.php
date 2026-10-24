<?php

namespace App\Support;

/**
 * Money — the ONE place amounts are formatted.
 *
 * A project's currency is set on its proposal and inherited by the
 * project, its invoices and its revenue shares. Amounts are NEVER
 * converted or mixed across currencies: every display goes through
 * here with the owning record's currency, and every total is kept
 * separate per currency.
 */
class Money
{
    /** Supported currencies: ISO code => display symbol + decimals. */
    public const META = [
        'PKR' => ['symbol' => 'Rs ',  'decimals' => 0],
        'USD' => ['symbol' => '$',    'decimals' => 2],
        'EUR' => ['symbol' => '€',    'decimals' => 2],
        'GBP' => ['symbol' => '£',    'decimals' => 2],
        'AED' => ['symbol' => 'AED ', 'decimals' => 2],
        'SAR' => ['symbol' => 'SAR ', 'decimals' => 2],
        'INR' => ['symbol' => '₹',    'decimals' => 0],
        'CAD' => ['symbol' => 'CA$',  'decimals' => 2],
        'AUD' => ['symbol' => 'A$',   'decimals' => 2],
    ];

    /** Supported ISO codes, in display order. */
    public static function codes(): array
    {
        return array_keys(self::META);
    }

    /** Symbol + decimals for a currency (unknown codes fall back to PKR). */
    public static function meta(?string $currency): array
    {
        return self::META[self::code($currency)];
    }

    /** Format an amount in its own currency, e.g. "Rs 45,000" / "$15,000.00" / "€1,200.00". */
    public static function format(float|int|null $amount, ?string $currency = 'PKR'): string
    {
        $meta = self::meta($currency);

        return $meta['symbol'] . number_format((float) ($amount ?? 0), $meta['decimals']);
    }

    /** Display prefix for compact spots and the revenue JS calculator. */
    public static function symbol(?string $currency = 'PKR'): string
    {
        return self::meta($currency)['symbol'];
    }

    /** Decimal places used when displaying this currency. */
    public static function decimals(?string $currency = 'PKR'): int
    {
        return self::meta($currency)['decimals'];
    }

    /** Normalise any input to a supported code (default PKR). */
    public static function code(?string $currency): string
    {
        $code = strtoupper((string) $currency);

        return isset(self::META[$code]) ? $code : 'PKR';
    }

    /** Select options: [code => code], or [code => "PKR (Rs)"] with symbols. */
    public static function options(bool $withSymbol = false): array
    {
        $options = [];
        foreach (self::META as $code => $meta) {
            $options[$code] = $withSymbol ? $code . ' (' . trim($meta['symbol']) . ')' : $code;
        }

        return $options;
    }

    /**
     * Current PKR rate for a currency, from Settings → Payments →
     * Exchange Rates. PKR is always 1. Returns null when no rate is
     * set, so converted totals can honestly leave that currency out.
     */
    public static function pkrRate(?string $currency): ?float
    {
        $code = self::code($currency);
        if ($code === 'PKR') {
            return 1.0;
        }

        try {
            $rates = app(\App\Settings\SiteSettings::class)->exchange_rates ?? [];
            $rate = (float) ($rates[$code] ?? 0);

            return $rate > 0 ? $rate : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** Convert an amount to PKR at the CURRENT Settings rate (null when unset). */
    public static function toPkr(float|int|null $amount, ?string $currency): ?float
    {
        $rate = self::pkrRate($currency);

        return $rate === null ? null : round((float) ($amount ?? 0) * $rate, 2);
    }
}
