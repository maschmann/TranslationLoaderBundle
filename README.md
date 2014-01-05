TranslationLoaderBundle
=========

TranslationLoaderBundle allows you to do several cool things with your symfony standart edition translations. It relies mostly on the Translator component and doctrine.
So you can...
  - import all your bundle's translations (if they are in the default path)
  - export all translations in your preferred format
  - add translations via commandline

Installation
----------

Add requirement to your composer.json
```php
"asm/translation-loader-bundle": "dev-master"
```
Update via composer and register the bundle.
```php
new Asm\TranslationLoaderBundle\AsmTranslationLoaderBundle(),
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
    database:
        entity_manager: <yourmanager_here> # default: null
```

###History & permissions
If you have users maintaining your translations, you might want to restict specific groups to specific actions.
Therefore a simple pre create/read/update/delete check (listener) is implemented, which throws according excpetions.
Also, if enabled, for each create, update or delete operation done on a translation entity via doctrine, a event subsciber adds a new line to a history table, referring to the currently logged-in user. It logs datetime and before/after value of the translation itself.

```yml
asm_translation_loader:
    history:
        enabled: false # default: false
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

###Generate dummy translation files
```sh
php app/console asm:translations:dummy
```
Since the TranslationLoader bases on files, you'd have to create empty files like "messages.en_US.db" for each language you want to use with your translation database. I have not yet found a nice way around that, so for the time being, you can use the dummy file generator which creates such a file for each message domain/locale you have in your db. The files will be placed in app/Resources/translations/*

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
php app/console asm:translations:dump
```
Check out your translation files in app/Resources/translations and if you remove all *.db files, your're back on file-mode!

###Testing
I've not yet completed my test-setup and be glad to get a little contribution here ;-)

Contributions
---------
I'd be happy if you like the bundle and help me take it to the next level and/or show me how much I f**ked up the code, help maintaining, contribute new features, ...

License
----

MIT


**Free Software, Hell Yeah!**
