TranslationLoaderBundle
=========

TranslationLoaderBundle allows you to do several cool things with your symfony standart edition translations. It relies mostly on the Translator component and doctrine.
So you can...
  - import all your bundle's translations (if they are in the default path)
  - export all translations in your preferred format
  - add translations via commandline

Installation
--------------
Add requirement to your composer.json
```php
"asm/translation-loader-bundle": "dev-master"
```
Update via composer and register the bundle
```php
new Asm\TranslationLoaderBundle\AsmTranslationLoaderBundle(),
```

Getting started
---------------


License
----

MIT


**Free Software, Hell Yeah!**
