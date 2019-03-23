<?php

declare(strict_types=1);

namespace Archette\Currency;

use Rixafy\Currency\Command\CurrencyUpdateCommand;
use Rixafy\Currency\CurrencyConfig;
use Rixafy\Currency\CurrencyFacade;
use Rixafy\Currency\CurrencyFactory;
use Rixafy\Currency\CurrencyRepository;

class CurrencyExtension extends \Nette\DI\CompilerExtension
{
    private $defaults = [
        'apiKey' => 'undefined',
        'apiService' => 'fixer',
        'baseCurrency' => 'EUR'
    ];

    public function beforeCompile()
    {
        $this->getContainerBuilder()->getDefinitionByType(\Doctrine\Common\Persistence\Mapping\Driver\AnnotationDriver::class)
            ->addSetup('addPaths', [['vendor/rixafy/currency']]);
    }

    public function loadConfiguration()
    {
        $this->validateConfig($this->defaults);

        $this->getContainerBuilder()->addDefinition($this->prefix('rixafy.currencyConfig'))
            ->setFactory(CurrencyConfig::class)
            ->addSetup('setApiKey', [$this->config['apiKey']])
            ->addSetup('setApiService', [$this->config['apiService']])
            ->addSetup('setBaseCurrency', [$this->config['baseCurrency']]);

        $this->getContainerBuilder()->addDefinition($this->prefix('rixafy.currencyFacade'))
            ->setFactory(CurrencyFacade::class);

        $this->getContainerBuilder()->addDefinition($this->prefix('rixafy.currencyRepository'))
            ->setFactory(CurrencyRepository::class);

        $this->getContainerBuilder()->addDefinition($this->prefix('rixafy.currencyFactory'))
            ->setFactory(CurrencyFactory::class);

        $this->getContainerBuilder()->addDefinition($this->prefix('rixafy.currencyUpdateCommand'))
            ->setFactory(CurrencyUpdateCommand::class);
    }
}