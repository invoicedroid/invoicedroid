# Version management package for Laravel.

This is a [SemVer](http://semver.org) compatible version management package for any software built on Laravel. It is a basically a fork of 
[Akaunting/Version](https://github.com/akaunting/version) package, and original copyright and thanks goes to those guys.

## Getting Started

### Publish

Publish config file.

```bash
php artisan vendor:publish --tag=version
```


### Configure

You can change the version information of your app from `config/version.php` file

## Usage

### version($method = null)

You can either enter the method like `version('short')` or leave it empty so you could firstly get the instance then call the methods like `version()->short()`

## Credits

- [Denis Duliçi](https://github.com/denisdulici)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.
