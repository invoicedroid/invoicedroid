# Currency formatting and conversion package for Laravel.

This package intends to provide tools for formatting and conversion monetary values. Not aimed for the public use as a package. It is a basically a fork of 
[Akaunting/Money](https://github.com/akaunting/money) package, and original copyright and thanks goes to those guys.

### Why not we are using Akaunting/Money package?

Because we need an integrated package for our app.

### Publish

Publish config file.

```bash
php artisan vendor:publish --tag=money
```

### Configure

You can change the currencies information of your app from `config/money.php` file

## Usage

```php
use InvoiceDroid\Money\Currency;
use InvoiceDroid\Money\Money;

echo Money::USD(500); // '$5.00' unconverted
echo new Money(500, new Currency('USD')); // '$5.00' unconverted
echo Money::USD(500, true); // '$500.00' converted
echo new Money(500, new Currency('USD'), true); // '$500.00' converted
```

### Advanced

```php
$m1 = Money::USD(500);
$m2 = Money::EUR(500);

$m1->getCurrency();
$m1->isSameCurrency($m2);
$m1->compare($m2);
$m1->equals($m2);
$m1->greaterThan($m2);
$m1->greaterThanOrEqual($m2);
$m1->lessThan($m2);
$m1->lessThanOrEqual($m2);
$m1->convert(Currency::GBP, 3.5);
$m1->add($m2);
$m1->subtract($m2);
$m1->multiply(2);
$m1->divide(2);
$m1->allocate([1, 1, 1]);
$m1->isZero();
$m1->isPositive();
$m1->isNegative();
$m1->format();
```

### Helpers

```php
money(500, 'USD')
currency('USD')
```

### Blade Directives

```php
@money(500, 'USD')
@currency('USD')
```

## Credits

- [Denis Duliçi](https://github.com/denisdulici)
- [Ricardo Gobbo de Souza](https://github.com/ricardogobbosouza)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.
