<?php

namespace Cknow\Money\Currencies;

use AppendIterator;
use IteratorIterator;
use Money\Currencies;
use Money\Currency;
use Money\Exception\UnknownCurrencyException;
use Traversable;

final  class AggregateCurrencies implements Currencies
{
    /**
     * @param  Currencies[]  $currencies
     */
    public function __construct(private array $currencies)
    {
    }

    public function contains(Currency $currency) : bool
    {
        foreach ($this->currencies as $currencies) {
            if ($currencies->contains($currency)) {
                return true;
            }
        }

        return false;
    }

    public function subunitFor(Currency $currency) : int
    {
        foreach ($this->currencies as $currencies) {
            if ($currencies->contains($currency)) {
                return $currencies->subunitFor($currency);
            }
        }

        throw new UnknownCurrencyException('Cannot find currency '.$currency->getCode());
    }

    public function getSymbol(Currency $currency) : ?string
    {

        foreach ($this->currencies as $currencies) {
            if ($currencies->contains($currency) && method_exists($currencies, 'getSymbol')) {
                return $currencies->getSymbol($currency);
            }
        }
        return null;
    }

    /** {@inheritDoc} */
    public function getIterator() : Traversable
    {
        $iterator = new AppendIterator();

        foreach ($this->currencies as $currencies) {
            $iterator->append(new IteratorIterator($currencies->getIterator()));
        }

        return $iterator;
    }
}
