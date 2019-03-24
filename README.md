# Currency
💱 CRUD model [rixafy/currency](https://github.com/Rixafy/Currency) ported to @nette framework.

# Installation
```
composer require archette/currency
```

Add extension to your neon configuration
```neon
extensions:
    archette.currency: Archette\Currency\CurrencyExtension

archette.currency:
    apiKey: <Your api key from fixer.io>
    apiService: fixer
    baseCurrency: EUR
```

and then run command ``php bin/console rixafy:currency:update`` for loading currencies and their rates to database (decimal points, separators and symbols are not yet included in update script)

# Example usage

Basic examples for working with extension

## Converting
```PHP
$eur = $this->currencyFacade->getByCode('EUR'); // returns Currency instance
$usd = $this->currencyFacade->getByCode('USD'); // returns Currency instance

$eur->convertFrom($usd, 100); // converts 100 USD to EUR, returns float
$eur->convertTo($usd, 100); // converts 100 EUR to USD, returns float
```

## Latte filters
There are 3 basic latte filters, **currency**, **currencyCode** and **currencyNumber**, all 3 filters have same parameters, $amount and $currencyCode (optional)

``{45.54|currency}`` will display ``$45.54`` if default or provided (CurrencyProvider::provide('USD')) currency is USD

``{45.54|currency:'EUR'}`` will display ``45,54 €``

``{45.54|currencyCode:'EUR'}`` will display ``45,54 EUR``

``{45.54|currencyNumber:'EUR'}`` will display ``45,54``

decimal point, hundred and thousand separator, symbol and code is saved in DB (table currency)

# Important

Extension requires implementation of Doctrine ORM in Nette Framework - https://github.com/nettrine/orm.

Extension requires implementation of symfony/console in Nette Framework - https://github.com/contributte/console.