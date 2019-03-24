<?php

declare(strict_types=1);

namespace Archette\Currency\Latte;

use Rixafy\Currency\CurrencyFacade;
use Rixafy\Currency\CurrencyProvider;

class CurrencyStringFilter
{
    /** @var CurrencyFacade */
    private $currencyFacade;

    /** @var CurrencyProvider */
    private $currencyProvider;

    public function __construct(CurrencyFacade $currencyFacade, CurrencyProvider $currencyProvider)
    {
        $this->currencyFacade = $currencyFacade;
        $this->currencyProvider = $currencyProvider;
    }

    /**
     * @param $amount
     * @param string|null $currency
     * @return string
     * @throws \Rixafy\Currency\Exception\CurrencyNotFoundException
     * @throws \Rixafy\Currency\Exception\CurrencyNotProvidedException
     */
    public function __invoke($amount, string $currency = null)
    {
        if ($currency === null) {
            $currency = $this->currencyProvider->getCurrency();

        } else {
            $currency = $this->currencyFacade->getByCode($currency);
        }

        return $currency->formatToString($amount);
    }
}