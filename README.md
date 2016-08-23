Clixy CMS (Admin)
================
[![Laravel 5.2](https://img.shields.io/badge/Laravel-5.2-orange.svg?style=flat-square)](http://laravel.com)
[![Source](http://img.shields.io/badge/source-popjelev/clixy.admin-blue.svg?style=flat-square)](https://github.com/popjelev/clixy.admin)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

Clixy is a custom CMS build on top of Laravel.
The package follows the FIG standards PSR-1, PSR-2, and PSR-4 to ensure a high level of interoperability between shared PHP code. At the moment the package is not unit tested, but is planned to be covered later down the road.

Documentation
-------------
You will find user friendly documentation in the wiki here: [Clixy CMS (Admin) Wiki](https://github.com/popjelev/clixy.admin/wiki)

Quick Installation
------------------
Begin by installing the package through Composer. The best way to do this is through your terminal via Composer itself:

```
composer require clixy/admin
```

Once this operation is complete, simply add the service provider to your project's `config/app.php` file.

### Service Provider
```php
Clixy\Admin\Providers\AdminServiceProvider::class
```

### Migrations, Seeds and Files
You'll need to publish vendor's files and run the provided migrations against your database.:
```
php artisan vendor:publish --force
composer dump-autoload
php artisan migrate --seed