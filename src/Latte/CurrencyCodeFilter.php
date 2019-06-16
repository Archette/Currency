<?php

declare(strict_types=1);

namespace Archette\Currency\Latte;

use Rixafy\Currency\CurrencyFacade;
use Rixafy\Currency\CurrencyProvider;
use Rixafy\Currency\Exception\CurrencyNotFoundException;
use Rixafy\Currency\Exception\CurrencyNotProvidedException;

class CurrencyCodeFilter
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
     * @throws CurrencyNotFoundException
     * @throws CurrencyNotProvidedException
     */
    public function __invoke($amount, string $currency = null)
    {
        if ($currency === null) {
            $currency = $this->currencyProvider->getCurrency();

        } else {
            $currency = $this->currencyFacade->getByCode($currency);
        }

        return $currency->formatToNumber($amount) . ' ' . $currency->getCode();
    }
}
