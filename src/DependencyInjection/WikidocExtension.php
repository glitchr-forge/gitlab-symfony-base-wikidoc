<?php

namespace Base\Wikidoc\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Config\Definition\Processor;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Base\Bundle\AbstractBaseExtension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

class WikidocExtension extends AbstractBaseExtension implements PrependExtensionInterface
{
    public function getConfiguration(array $config, ContainerBuilder $container): WikidocConfiguration
    {
        return new WikidocConfiguration();
    }

    /**
     * The package's own Doctrine ORM mapping: AdminDocument/DevDocument/
     * UserDocument have no App\Entity counterpart to ride the alias trick
     * base-bundle's own entities use, so they need a real mapping entry.
     */
    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig('doctrine', [
            'orm' => [
                'mappings' => [
                    'Wikidoc' => [
                        'is_bundle' => false,
                        'type' => 'attribute',
                        'dir' => \dirname(__DIR__) . '/Entity',
                        'prefix' => 'Base\\Wikidoc\\Entity',
                        'alias' => 'Wikidoc',
                    ],
                ],
            ],
        ]);
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        // NB: package root, not src/Resources/config - this bundle has no
        // Resources directory. PhpFileLoader (not Xml): Symfony 8 removed
        // XmlFileLoader from dependency-injection.
        $loader = new PhpFileLoader($container, new FileLocator(dirname(__DIR__, 2) . '/config'));
        $loader->load('services.php');

        $processor = new Processor();
        $configuration = new WikidocConfiguration();
        $config = $processor->processConfiguration($configuration, $configs);
        $this->setConfiguration($container, $config, $configuration->getTreeBuilder()->buildTree()->getName());

        // NB: no setConfigurationAliases() - that segment-swap aliasing
        // duplicates every service definition (see base-bundle-admin).
    }
}
