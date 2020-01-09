<?php

declare(strict_types=1);

namespace Archette\Currency;

use Archette\Currency\Latte\CurrencyCodeFilter;
use Archette\Currency\Latte\CurrencyNumberFilter;
use Archette\Currency\Latte\CurrencyStringFilter;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\FactoryDefinition;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\Schema\Expect;
use Rixafy\Currency\Command\CurrencyUpdateCommand;
use Rixafy\Currency\CurrencyConfig;
use Rixafy\Currency\CurrencyFacade;
use Rixafy\Currency\CurrencyFactory;
use Rixafy\Currency\CurrencyProvider;
use Rixafy\Currency\CurrencyRepository;
use Nette\Schema\Schema;

class CurrencyExtension extends CompilerExtension
{
	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'apiKey' => Expect::string(),
			'apiService' => Expect::string('fixer'),
			'baseCurrency' => Expect::string('EUR'),
		]);
	}

    public function beforeCompile(): void
    {
    	/** @var ServiceDefinition $serviceDefinition */
        $serviceDefinition = $this->getContainerBuilder()->getDefinitionByType(MappingDriver::class);
        $serviceDefinition->addSetup('addPaths', [['vendor/rixafy/currency']]);
    }

    public function loadConfiguration(): void
    {
        $this->getContainerBuilder()->addDefinition($this->prefix('currencyConfig'))
            ->setFactory(CurrencyConfig::class, [
				$this->config->baseCurrency,
				$this->config->apiService,
				$this->config->apiKey
			]);

        $this->getContainerBuilder()->addDefinition($this->prefix('currencyFacade'))
            ->setFactory(CurrencyFacade::class);

        $this->getContainerBuilder()->addDefinition($this->prefix('currencyRepository'))
            ->setFactory(CurrencyRepository::class);

        $this->getContainerBuilder()->addDefinition($this->prefix('currencyFactory'))
            ->setFactory(CurrencyFactory::class);

        $this->getContainerBuilder()->addDefinition($this->prefix('currencyProvider'))
            ->setFactory(CurrencyProvider::class);

		$this->getContainerBuilder()->addDefinition($this->prefix('currencyUpdateCommand'))
			->setFactory(CurrencyUpdateCommand::class)
			->addTag('console.command', 'rixafy:currency:update');

        $stringFilter = $this->getContainerBuilder()->addDefinition($this->prefix('currencyStringFilter'))
            ->setFactory(CurrencyStringFilter::class);

        $codeFilter = $this->getContainerBuilder()->addDefinition($this->prefix('currencyCodeFilter'))
            ->setFactory(CurrencyCodeFilter::class);

        $numberFilter = $this->getContainerBuilder()->addDefinition($this->prefix('currencyNumberFilter'))
            ->setFactory(CurrencyNumberFilter::class);

		/** @var FactoryDefinition $latteFactory */
		$latteFactory = $this->getContainerBuilder()->getDefinitionByType(ILatteFactory::class);

		$latteFactory->getResultDefinition()->addSetup('addFilter', ['currency', $stringFilter]);
		$latteFactory->getResultDefinition()->addSetup('addFilter', ['currencyCode', $codeFilter]);
        $latteFactory->getResultDefinition()->addSetup('addFilter', ['currencyNumber', $numberFilter]);
    }
}
