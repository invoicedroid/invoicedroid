# Persistent settings package for Laravel

Not aimed for the public use as a package. It is a basically a fork of 
[Akaunting/Setting](https://github.com/akaunting/setting) package, and original copyright and thanks goes to those guys.

### Why not we are using Akaunting/Setting package?

Because we need an integrated package for our app.

### Publish

Publish config file.

```bash
php artisan vendor:publish --tag=setting
```

### Configure

You can change the currencies information of your app from `config/setting.php` file

## Usage

```php
Setting::get('foo', 'default');
Setting::get('nested.element');
Setting::set('foo', 'bar');
Setting::forget('foo');
$settings = Setting::all();

setting('foo', 'default');
setting('nested.element');
setting(['foo' => 'bar']);
setting()->forget('foo');
$settings = setting()->all();
```

## Credits

- [Denis Duliçi](https://github.com/denisdulici)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.
