<?php

declare(strict_types=1);

namespace Archette\Currency;

use Rixafy\Currency\CurrencyConfig;

class CurrencyExtension extends \Nette\DI\CompilerExtension
{
    private $defaults = [
        'apiKey' => 'undefined',
        'apiService' => 'fixer',
        'baseCurrency' => 'EUR'
    ];

    public function loadConfiguration()
    {
        $this->validateConfig($this->defaults);

        $this->getContainerBuilder()->addDefinition($this->prefix('currencyConfig'))
            ->setFactory(CurrencyConfig::class)
            ->addSetup('setApiKey', [$this->config['apiKey']])
            ->addSetup('setApiService', [$this->config['apiService']])
            ->addSetup('setBaseCurrency', [$this->config['baseCurrency']]);
    }
}