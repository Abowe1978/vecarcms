<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Get all supported currencies
     */
    public static function getSupportedCurrencies(): array
    {
        return config('currencies.supported', []);
    }

    /**
     * Get default currency
     */
    public static function getDefaultCurrency(): string
    {
        return config('currencies.default', 'GBP');
    }

    /**
     * Get all available currencies with details
     */
    public static function getAllCurrencies(): array
    {
        return config('currencies.list', []);
    }

    /**
     * Get currency details by code
     */
    public static function getCurrency(string $code): ?array
    {
        $currencies = self::getAllCurrencies();
        return $currencies[strtoupper($code)] ?? null;
    }

    /**
     * Get currency name by code
     */
    public static function getCurrencyName(string $code): ?string
    {
        $currency = self::getCurrency($code);
        return $currency['name'] ?? null;
    }

    /**
     * Get currency symbol by code
     */
    public static function getCurrencySymbol(string $code): ?string
    {
        $currency = self::getCurrency($code);
        return $currency['symbol'] ?? null;
    }

    /**
     * Check if currency is supported
     */
    public static function isSupported(string $code): bool
    {
        return in_array(strtoupper($code), self::getSupportedCurrencies());
    }

    /**
     * Check if currency exists in the list
     */
    public static function exists(string $code): bool
    {
        return array_key_exists(strtoupper($code), self::getAllCurrencies());
    }

    /**
     * Format amount with currency symbol
     */
    public static function format(float $amount, string $currency, bool $showCode = false): string
    {
        $symbol = self::getCurrencySymbol($currency);
        $code = strtoupper($currency);
        
        $formatted = number_format($amount, 2);
        
        if ($symbol) {
            $result = $symbol . $formatted;
        } else {
            $result = $formatted . ' ' . $code;
        }
        
        if ($showCode && $symbol) {
            $result .= ' ' . $code;
        }
        
        return $result;
    }

    /**
     * Get supported currencies as options array for forms
     */
    public static function getSupportedCurrencyOptions(): array
    {
        $supported = self::getSupportedCurrencies();
        $options = [];
        
        foreach ($supported as $code) {
            $currency = self::getCurrency($code);
            if ($currency) {
                $options[$code] = $code . ' - ' . $currency['name'];
            }
        }
        
        return $options;
    }

    /**
     * Get all currencies as options array for forms
     */
    public static function getAllCurrencyOptions(): array
    {
        $currencies = self::getAllCurrencies();
        $options = [];
        
        foreach ($currencies as $code => $currency) {
            $options[$code] = $code . ' - ' . $currency['name'];
        }
        
        return $options;
    }

    /**
     * Validate currency code
     */
    public static function validate(string $code): bool
    {
        return self::exists($code);
    }

    /**
     * Get currency validation rule for Laravel
     */
    public static function getValidationRule(): string
    {
        $supported = implode(',', self::getSupportedCurrencies());
        return "in:{$supported}";
    }

    /**
     * Convert currency code to enum value for database
     */
    public static function toEnumValue(string $code): string
    {
        return strtoupper($code);
    }

    /**
     * Get enum values for migration
     */
    public static function getEnumValues(): array
    {
        return self::getSupportedCurrencies();
    }
} 