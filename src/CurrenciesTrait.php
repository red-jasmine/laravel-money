<?php

namespace Cknow\Money;

use Cknow\Money\Currencies\CurrencyList;
use Cknow\Money\Currencies\ISOCurrencies;
use Cknow\Money\Currencies\AggregateCurrencies;
use InvalidArgumentException;
use Money\Currencies;
use Money\Currencies\BitcoinCurrencies;

use Money\Currency;

trait CurrenciesTrait
{
    /**
     * @var string
     */
    protected static $currency;

    /**
     * @var Currencies
     */
    protected static $currencies;

    /**
     * Parse currency.
     *
     * @param  Currency|string  $currency
     *
     * @return Currency
     */
    public static function parseCurrency($currency)
    {
        if (is_string($currency)) {
            return new Currency($currency);
        }

        return $currency;
    }

    public static function getCurrencySymbol($currency) : ?string
    {

        if (is_string($currency)) {
            $currency = new Currency($currency);
        }
        return static::getCurrencies()->getSymbol($currency);
    }

    /**
     * Validates currency.
     *
     * @param  Currency|string  $currency
     *
     * @return bool
     */
    public static function isValidCurrency($currency)
    {
        return static::getCurrencies()->contains(static::parseCurrency($currency));
    }

    /**
     * Get default currency.
     *
     * @return string
     */
    public static function getDefaultCurrency()
    {
        if (!isset(static::$currency)) {
            static::setDefaultCurrency(config('money.defaultCurrency', config('app.currency', 'CNY')));
        }

        return static::$currency;
    }

    /**
     * Set default currency.
     *
     * @param  string  $currency
     */
    public static function setDefaultCurrency($currency)
    {
        static::$currency = $currency;
    }

    /**
     * Get ISO currencies.
     *
     * @return array
     */
    public static function getISOCurrencies()
    {
        return (new ISOCurrencies)->getCurrencies();
    }

    /**
     * Get currencies.
     *
     * @return Currencies
     */
    public static function getCurrencies()
    {
        if (!isset(static::$currencies)) {
            static::setCurrencies(config('money.currencies', []));
        }

        return static::$currencies;
    }

    /**
     * Set currencies.
     *
     * @param  Currencies|array|null  $currencies
     */
    public static function setCurrencies($currencies)
    {
        static::$currencies = ($currencies instanceof Currencies)
            ? $currencies
            : static::makeCurrencies($currencies);
    }

    /**
     * Make currencies according to array derived from config or anywhere else.
     *
     * @param  array|null  $currenciesConfig
     *
     * @return Currencies
     */
    private static function makeCurrencies($currenciesConfig)
    {
        if (!$currenciesConfig || !is_array($currenciesConfig)) {
            // for backward compatibility
            return new ISOCurrencies;
        }

        $currenciesList = [];

        if ($currenciesConfig['iso'] ?? false) {

            $currenciesList[] = static::makeCurrenciesForSource(
                $currenciesConfig['iso'],
                new ISOCurrencies,
                'ISO'
            );

        }

        if ($currenciesConfig['bitcoin'] ?? false) {

            $currenciesList[] = static::makeCurrenciesForSource(
                $currenciesConfig['bitcoin'],
                new BitcoinCurrencies,
                'Bitcoin'
            );
        }

        if ($currenciesConfig['custom'] ?? false) {
            $currenciesList[] = new CurrencyList($currenciesConfig['custom']);
        }

        return new AggregateCurrencies($currenciesList);
    }

    /**
     * Make currencies list according to array for specified source.
     *
     * @param  array|string  $config
     * @param  string  $sourceName
     *
     * @return Currencies
     *
     * @throws InvalidArgumentException
     */
    private static function makeCurrenciesForSource($config, Currencies $currencies, $sourceName)
    {
        if ($config === 'all') {
            return $currencies;
        }

        if (is_array($config)) {
            $lisCurrencies = [];

            foreach ($config as $index => $currencyCode) {

                $currency = static::parseCurrency($currencyCode);

                if (!$currencies->contains($currency)) {
                    throw new InvalidArgumentException(
                        sprintf('Unknown %s currency code: %s', $sourceName, $currencyCode)
                    );
                }
                if ($currencies instanceof BitcoinCurrencies) {

                    $lisCurrencies[$currency->getCode()] = [
                        'name'                => $currency->getCode(),
                        'code'                => $currency->getCode(),
                        'minorUnit'           => $currencies->subunitFor($currency),
                        'subunit'             => pow(10, $currencies->subunitFor($currency)),
                        'symbol'              => $currencies::SYMBOL,
                        'symbol_first'        => true,
                        'decimal_mark'        => '.',
                        'thousands_separator' => ',',
                    ];
                } else {
                    $lisCurrencies[$currency->getCode()] = $currencies->getCurrencies()[$currencyCode];
                }


            }

            return new CurrencyList($lisCurrencies);
        }

        throw new InvalidArgumentException(
            sprintf('%s config must be an array or \'all\'', $sourceName)
        );
    }
}
