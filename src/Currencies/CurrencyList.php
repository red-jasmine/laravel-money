<?php

namespace Cknow\Money\Currencies;

use ArrayIterator;
use Money\Currencies;
use Money\Currency;
use Money\Exception\UnknownCurrencyException;
use Traversable;

/**
 * A list of custom currencies.
 */
final class CurrencyList implements Currencies
{
    /**
     * Map of currencies and their sub-units indexed by code.
     *
     * @phpstan-var array<non-empty-string, int>
     */
    private array $currencies;

    /** @phpstan-param array<non-empty-string, non-negative-int> $currencies */
    public function __construct(array $currencies)
    {
        $this->currencies = $currencies;
    }

    public function contains(Currency $currency) : bool
    {
        return isset($this->currencies[$currency->getCode()]);
    }

    public function subunitFor(Currency $currency) : int
    {
        if (!$this->contains($currency)) {
            throw new UnknownCurrencyException('Cannot find ISO currency '.$currency->getCode());
        }

        return is_int($this->currencies[$currency->getCode()]) ? $this->currencies[$currency->getCode()] : $this->currencies[$currency->getCode()]['minorUnit'];
    }

    public function getSymbol(Currency $currency) : ?string
    {
        if (!$this->contains($currency)) {
            throw new UnknownCurrencyException('Cannot find ISO currency '.$currency->getCode());
        }

        return $this->currencies[$currency->getCode()]['symbol'] ?? null;
    }


    /** {@inheritDoc} */
    public function getIterator() : Traversable
    {
        return new ArrayIterator(
            array_map(
                static function ($code) {
                    return new Currency($code);
                },
                array_keys($this->currencies)
            )
        );
    }
}