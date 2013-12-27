<?php
/**
 * @namespace Asm\TranslationLoaderBundle\TestCase
 */
namespace Asm\TranslationLoaderBundle\TestCase;

use Symfony\Bundle\FrameworkBundle\Tests\Functional\WebTestCase;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;

/**
 * Class DatabaseTestCaseWeb
 *
 * @package Asm\TranslationLoaderBundle\TestCase
 * @author marc aschmann <maschmann@gmail.com>
 * @uses Symfony\Bundle\FrameworkBundle\Tests\Functional\WebTestCase
 * @uses Doctrine\Common\DataFixtures\Loader
 * @uses Doctrine\Common\DataFixtures\Purger\ORMPurger
 * @uses Doctrine\Common\DataFixtures\Executor\ORMExecutor
 */
abstract class DatabaseTestCase extends WebTestCase
{

    /**
     * initialize fixtures and stuff
     */
    public function setUp()
    {
        self::$kernel = $this->createKernel();
        self::$kernel->boot();

        // initialize database and generate our tables
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:update --force');
        self::runCommand('doctrine:fixtures:load -n --purge-with-truncate');

        // prepare database and load fixtures
        $em     = self::$kernel->getContainer()->get('doctrine')->getManager();
        $loader = new Loader();
        $loader->loadFromDirectory('../../DataFixtures/ORM/');
        $purger   = new ORMPurger($em);
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures());
    }


    /**
     * @return mixed
     */
    public function getContainer()
    {
        return self::$kernel->getContainer();
    }
}
