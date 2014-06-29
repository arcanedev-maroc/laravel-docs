#Laravel Docs : Offline + Multilang + Multi-versions
Laravel Docs is an offline Multilang & Multi-versions documentation manager for Laravel 4.

###Contributing
If you have any suggestions or improvements feel free to create an issue or create a Pull Request.

## Installation
Simply add the package to your `composer.json` file and run `composer update`.

```javascript
{
    ...
    "require-dev": {
        ...
        "arcanedev-maroc/laravel-docs": "dev-master"
        ...
    },
}
```

Update composer:
```
$ php composer.phar update
```

Add the provider to your `app/config/app.php`:

```php
'providers' => array(

    ...
    'Arcanedev\LaravelDocs\LaravelDocsServiceProvider',

),
```

Publish package assets:

```
$ php artisan asset:publish arcanedev-maroc/laravel-docs
```

(Optional) Publish package config:

```
$ php artisan config:publish arcanedev-maroc/laravel-docs
```

##Configuration

 * `enabled`: (boolean) Enable or disable this package
 * `prefix`: (Default : docs) prefix used to access the main documentation page via the route. (Exemple : http://loc.dev/docs)
 * `use-sessions`: (boolean) Use the session to remember last selected locale.
 * `locales`: (array) Choose your documment locales.
 * `versions`: (array) Choose your documment versions
 * `docs`:
     - `display-rows`: (boolean) Display the main page in rows or columns
 * `layout`: (string) The template path

###Documentations Path
All the documentations is located in `vendor/arcanedev/laravel-docs/src/docs`.
If you want add a docs for a specific language:
* Use this structure `base_docs_path/jp/4.1/` or `base_docs_path/es/4.2/` (`base_docs_path`:`vendor/arcanedev/laravel-docs/src/docs`).
* After you added your docs, you need to translate the table of contents and some texts, check the `vendor/arcanedev/laravel-docs/src/lang/` folder, i'm sure you can add yours.
* After you done all this stuff, make sure to update the configuration file.
* If the pacakge explodes, call 911.

## To do
* Update README.md
* Add more docs (more languages, more versions & more....).
* Add RTL Locales Support.
* Refactoring + tests.

## Credits
The available docs within this package is from:
* EN : https://github.com/laravel/docs
* FR : https://github.com/laravel-france/documentation
