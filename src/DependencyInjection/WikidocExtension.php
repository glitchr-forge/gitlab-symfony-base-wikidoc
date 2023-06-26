<?php

namespace Base\Wikidoc\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\Definition\Processor;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Base\Bundle\AbstractBaseExtension;

/**
 *
 */
class WikidocExtension extends AbstractBaseExtension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        // Format XML
        $loader = new XmlFileLoader($container, new FileLocator(dirname(__DIR__) . '/Resources/config'));
        $loader->load('services.xml');

        $processor = new Processor();
        $configuration = new WikidocConfiguration();
        $config = $processor->processConfiguration($configuration, $configs);
        $this->setConfiguration($container, $config, $configuration->getTreeBuilder()->getRootNode()->getNode()->getName());

        $this->setConfigurationAliases($container);
    }
}
