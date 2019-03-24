<?php

declare(strict_types=1);

namespace Archette\Currency;

use Archette\Currency\Latte\CurrencyCodeFilter;
use Archette\Currency\Latte\CurrencyNumberFilter;
use Archette\Currency\Latte\CurrencyStringFilter;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Rixafy\Currency\Command\CurrencyUpdateCommand;
use Rixafy\Currency\CurrencyConfig;
use Rixafy\Currency\CurrencyFacade;
use Rixafy\Currency\CurrencyFactory;
use Rixafy\Currency\CurrencyProvider;
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

        $this->getContainerBuilder()->addDefinition($this->prefix('currencyConfig'))
            ->setFactory(CurrencyConfig::class)
            ->addSetup('setApiKey', [$this->config['apiKey']])
            ->addSetup('setApiService', [$this->config['apiService']])
            ->addSetup('setBaseCurrency', [$this->config['baseCurrency']]);

        $this->getContainerBuilder()->addDefinition($this->prefix('currencyFacade'))
            ->setFactory(CurrencyFacade::class);

        $this->getContainerBuilder()->addDefinition($this->prefix('currencyRepository'))
            ->setFactory(CurrencyRepository::class);

        $this->getContainerBuilder()->addDefinition($this->prefix('currencyFactory'))
            ->setFactory(CurrencyFactory::class);

        $this->getContainerBuilder()->addDefinition($this->prefix('currencyUpdateCommand'))
            ->setFactory(CurrencyUpdateCommand::class);

        $this->getContainerBuilder()->addDefinition($this->prefix('currencyProvider'))
            ->setFactory(CurrencyProvider::class);

        $stringFilter = $this->getContainerBuilder()->addDefinition($this->prefix('currencyStringFilter'))
            ->setFactory(CurrencyStringFilter::class);

        $this->getContainerBuilder()->getDefinitionByType(ILatteFactory::class)
            ->addSetup('addFilter', ['currency', $stringFilter]);

        $codeFilter = $this->getContainerBuilder()->addDefinition($this->prefix('currencyCodeFilter'))
            ->setFactory(CurrencyCodeFilter::class);

        $this->getContainerBuilder()->getDefinitionByType(ILatteFactory::class)
            ->addSetup('addFilter', ['currencyCode', $codeFilter]);

        $numberFilter = $this->getContainerBuilder()->addDefinition($this->prefix('currencyNumberFilter'))
            ->setFactory(CurrencyNumberFilter::class);

        $this->getContainerBuilder()->getDefinitionByType(ILatteFactory::class)
            ->addSetup('addFilter', ['currencyNumber', $numberFilter]);
    }
}