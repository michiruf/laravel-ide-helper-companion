# Laravel IDE Helper Companion

## Installation

1. Add the github repository and the dev dependency in your composer.json like so:
   ```json
   {
       // ...
       "repositories": [
           {
               "type": "vcs",
               "url": "https://github.com/michiruf/laravel-ide-helper-companion.git"
           }
       ],
       "require-dev": {
           // ...
           "michiruf/laravel-ide-helper-companion": "dev-main",
           // ...
       }
   }
   ```
2. Perform a composer update for the package
   ```shell
   composer update michiruf/laravel-ide-helper-companion
   ```
   
## Usage

Command:
```shell
php artisan ide-helper-companion
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
