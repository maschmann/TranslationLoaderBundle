<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Tests\DependencyInjection;

use Asm\TranslationLoaderBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var Processor
     */
    private $processor;

    protected function setUp()
    {
        $this->configuration = new Configuration();
        $this->processor = new Processor();
    }

    public function testLocaleWithoutDomain()
    {
        $configs = $this->buildResourcesConfig(array('en' => null));
        $config = $this->process($configs);

        $this->assertEquals(array('en' => array(null)), $config['resources']);
    }

    public function testLocaleWithOneDomain()
    {
        $configs = $this->buildResourcesConfig(array('en' => 'foo'));
        $config = $this->process($configs);

        $this->assertEquals(array('en' => array('foo')), $config['resources']);
    }

    public function testLocaleWithMultipleDomains()
    {
        $configs = $this->buildResourcesConfig(array('en' => array('foo', 'bar')));
        $config = $this->process($configs);

        $this->assertEquals(array('en' => array('foo', 'bar')), $config['resources']);
    }

    public function testLocaleWithoutDomainFromXml()
    {
        $configs = $this->buildResourcesConfig(array(array('locale' => 'en')));
        $config = $this->process($configs);

        $this->assertEquals(array('en' => array(null)), $config['resources']);
    }

    public function testLocaleWithOneDomainFromXml()
    {
        $configs = $this->buildResourcesConfig(array(array('locale' => 'en', 'domain' => 'foo')));
        $config = $this->process($configs);

        $this->assertEquals(array('en' => array('foo')), $config['resources']);
    }

    public function testLocaleWithMultipleDomainsFromXml()
    {
        $configs = $this->buildResourcesConfig(array(
            array(
                'locale' => 'en',
                'domain' => array('foo', 'bar'),
            )
        ));
        $config = $this->process($configs);

        $this->assertEquals(array('en' => array('foo', 'bar')), $config['resources']);
    }

    private function process(array $configs)
    {
        return $this->processor->processConfiguration($this->configuration, $configs);
    }

    private function buildResourcesConfig(array $resources)
    {
        return array(
            array(
                'resources' => $resources,
            )
        );
    }
}
 