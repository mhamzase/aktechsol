<?php

namespace App\Support;

/**
 * Money — the ONE place amounts are formatted.
 *
 * The business works in exactly two currencies: PKR and USD. A
 * project's currency is set on its proposal and inherited by the
 * project, its invoices and its revenue shares; amounts are NEVER
 * converted or mixed, so every display goes through here with the
 * owning record's currency.
 */
class Money
{
    /** Supported currency codes. */
    public const CURRENCIES = ['PKR', 'USD'];

    /**
     * Format an amount in its own currency:
     * PKR → "Rs 45,000" (whole rupees), USD → "$15,000.00".
     */
    public static function format(float|int|null $amount, ?string $currency = 'PKR'): string
    {
        $amount = (float) ($amount ?? 0);

        return self::isUsd($currency)
            ? '$' . number_format($amount, 2)
            : 'Rs ' . number_format($amount);
    }

    /** Display prefix for compact spots and the revenue JS calculator. */
    public static function symbol(?string $currency = 'PKR'): string
    {
        return self::isUsd($currency) ? '$' : 'Rs ';
    }

    /** Decimal places used when displaying this currency. */
    public static function decimals(?string $currency = 'PKR'): int
    {
        return self::isUsd($currency) ? 2 : 0;
    }

    public static function isUsd(?string $currency): bool
    {
        return strtoupper((string) $currency) === 'USD';
    }

    /** Normalise any input to a supported code (default PKR). */
    public static function code(?string $currency): string
    {
        $code = strtoupper((string) $currency);

        return in_array($code, self::CURRENCIES, true) ? $code : 'PKR';
    }
}
