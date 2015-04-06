TranslationLoaderBundle
=========

[![Build Status](https://travis-ci.org/maschmann/TranslationLoaderBundle.svg?branch=master)](https://travis-ci.org/maschmann/TranslationLoaderBundle)
[![Latest Stable Version](https://poser.pugx.org/asm/translation-loader-bundle/v/stable.png)](https://packagist.org/packages/asm/translation-loader-bundle)
[![Total Downloads](https://poser.pugx.org/asm/translation-loader-bundle/downloads.png)](https://packagist.org/packages/asm/translation-loader-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/maschmann/TranslationLoaderBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/maschmann/TranslationLoaderBundle/?branch=master)

[![knpbundles.com](http://knpbundles.com/maschmann/TranslationLoaderBundle/badge-short)](http://knpbundles.com/maschmann/TranslationLoaderBundle) [![phpci build status](http://phpci.br0ken.de/build-status/image/1)](http://phpci.br0ken.de)

TranslationLoaderBundle allows you to do several cool things with your symfony standard edition translations. It relies mostly on the Translator component and doctrine.
So you can...
  - import all your bundle's translations (if they are in the default path)
  - export all translations in your preferred format
  - add translations via commandline

Installation
----------

1. Add `asm/translation-loader-bundle` as a dependency of your project:

   ```bash
   $ composer require asm/translation-loader-bundle "~1.0"
   ```

1. Register the bundle in the kernel:

   ```php
   // app/AppKernel.php
   // ...

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Asm\TranslationLoaderBundle\AsmTranslationLoaderBundle(),
        );

        return $bundles;
    }
   ```
1. If you want the admin gui, add routing config:

    ```yaml
    # app/config/routing.yml
    
    asm_translation_loader.gui:
        resource: "@AsmTranslationLoaderBundle/Resources/config/routing.yml"
        prefix:   /translation
    ```

How does it work?
----------

It's quite simple.
For every cache clear you do, the translation dumper is generating a MessageCatalogue in your cache directory from which the application later retrieves all your translations and according fallbacks. So far, so good... We're not changing that but moving the translations from files to a flat database table, so they're (in some cases) easier to change and maintain.
After installing and following the setup steps, your cache MessageCatalogue will be created directly from the database.

Getting started
----------
###EntityManager
You can tell the bundle to use an EntityManager other than your default manager.
Therefore you need to add a configuration node to your app/config/config.yml
```yml
asm_translation_loader:
    driver: orm
    database:
        entity_manager: <yourmanager_here> # default: null
```

###History
If enabled, the history feature listens on doctrine actions and adds an entry for each change, adding the name of the currently logged-in user.

```yml
asm_translation_loader:
    history: true # default false
```

###Setup the translation table
```sh
php app/console doctrine:schema:update --force
```
This will create the translation table for you, so you can start importing.

###Importing all translations to database
```sh
php app/console asm:translations:import
```
__Be careful:__ The import might take a while, since the process checks for each translation if it's already present and then either inserts or updates. I'm open to suggestions here ;-)
This command also has a "-c" so you can clear the database first. might be a bit faster for larger imports if your table is already filled.

###Generate dummy translation files or using configuration
```sh
php app/console asm:translations:dummy
```
Since the TranslationLoader bases on files, you'd have to create empty files like "messages.en_US.db" for each language you want to use with your translation database. I have not yet found a nice way around that, so for the time being, you can use the dummy file generator which creates such a file for each message domain/locale you have in your db. The files will be placed in app/Resources/translations/*
The other option is configuring the loader for specific locales and message domains:
```yml
asm_translation_loader:
    resources:
        fr: [ foo, bar ]
        de: baz
        en: ~
    driver: orm
    history: true # default false
    database:
        entity_manager: default
```

###Delete your old translation files
After checking your translation table, you should delete all translation files from your custom bundle's directories.
Type a short
```sh
php app/console ca:cl
```
and check your symfony cache dir for the brand new MessageCatalogue we've now filled from the db :-)

###Funstuff
If you're tired of database-translations, don't despair: Just use the file dumper and re-generate your files!
```sh
php app/console help asm:translations:dump
```
Check out your translation files in app/Resources/translations and if you remove all *.db files, your're back on file-mode!

###Testing
I've not yet completed my test-setup and be glad to get a little contribution here ;-)

Contributions
---------
Great thanks got to xabbuh for supporting with refactorings and testing (implementation)!
Anyone who wants to contribute is greatly welcome!

License
----

AsmTranslationLoaderBundle is licensed under the MIT license. See the [LICENSE](Resources/meta/LICENSE) for the full license text.


**Free Software, Hell Yeah!**
