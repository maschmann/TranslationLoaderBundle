<?php
/**
 * @namespace Asm\TranslationLoaderBundle\TestCase
 */
namespace Asm\TranslationLoaderBundle\TestCase;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;

/**
 * Class DatabaseTestCaseWeb
 *
 * @package Asm\TranslationLoaderBundle\TestCase
 * @author marc aschmann <maschmann@gmail.com>
 * @uses Symfony\Bundle\FrameworkBundle\Test\WebTestCase
 * @uses Doctrine\Common\DataFixtures\Loader
 * @uses Doctrine\Common\DataFixtures\Purger\ORMPurger
 * @uses Doctrine\Common\DataFixtures\Executor\ORMExecutor
 */
abstract class DatabaseTestCase extends WebTestCase
{
    /**
     * @var
     */
    private static $application;


    /**
     * initialize fixtures and stuff
     */
    protected function setUp()
    {
        parent::setUp();

        $this->createKernel();

        // initialize database and generate our tables
        $this->runCommand('doctrine:database:create');
        $this->runCommand('doctrine:schema:update', array('--force' => true));
        $this->runCommand('doctrine:fixtures:load', array('-n', '--purge-with-truncate' => true));

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


    /**
     * run a symfony command with CommandTester wrapper
     *
     * @param string $command command to test
     * @param array $params parameters for command
     * @return \Asm\TranslationLoaderBundle\TestCase\Symfony\Component\Console\Tester\CommandTester
     */
    public function runCommand($command, array $params=array())
    {
        /** @var \Symfony\Bundle\FrameworkBundle\Console\Application $application */
        $application = $this->initApplication();
        // using command tester here, because it's quite easy and I need it later...
        /** @var Symfony\Component\Console\Tester\CommandTester $commandTester */
        $commandTester = new CommandTester($application->find($command));
        $commandTester->execute($params);

        return $commandTester;
    }


    /**
     * initialize application with kernel
     */
    private function initApplication()
    {
        if (empty(self::$application)) {
            self::$application = new Application(self::$kernel);
        }

        return self::$application;
    }
}
