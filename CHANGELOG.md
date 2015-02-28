CHANGELOG
=========

1.0.7
-----
* Cleanup bundle structure
* Fixed unittests

1.0.6
-----

* Fixed FileLoaderResolver and compiler pass
* Added draft for cache refreshing functionality

1.0.5
-----

* make the bundle compatible with Symfony 2.2 again

* ensure that tests are also run with Symfony 2.2

1.0.4
-----

* resolve circular dependency between the `doctrine.orm.default_entity_manager`
  (or any other Doctrine ORM entity manager service) and `security_context` services
  when using the translation history feature

* disable the Form and Validator extension when running tests to be compatible with
  Symfony 2.5.4

* do not register the translation history subscriber service when the feature was
  not enabled

* added tests for the `asm:translations:dummy` command

* added an option to configure translation file loaders based on the file extension

* removed unused translation fixtures loader

* map files with `.xlf` extensions to the `XliffFileLoader`

1.0.3
-----

* fixed the initial import of translation messages

* made sure that the entity manager is flushed after a translation entity has been
  removed

* fixed the retrieval of the service container in container aware commands

1.0.2
-----

* added check that a service definition exists before adding method calls to it

* added the XML schema definition for the dependency injection container extension
  configuration

* use a compiler pass to add the database loader as a resource to the translator

* update only translations that have been changed when running the `asm:translations:import`
  command

* removed executable permissions from some files

* added test for translation commands

* fixed dependencies (moved the `symfony/framework-bundle` dependency to the dev
  dependencies and require the required component packages explicitly)

* added a `--format` option to the `asm:translations:dump` command

* made the `locale` and `domain` arguments of the `asm:translations:dump` command
  optional (now, use the `--locale` and `--domain` options instead)

1.0.1
-----

* remove unnecessary doubled `asm_translation_loader.translation_manager` service
  definition
