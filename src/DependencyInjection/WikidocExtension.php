<?php

namespace Base\Wikidoc\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Config\Definition\Processor;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Base\Bundle\AbstractBaseExtension;
use Base\Wikidoc\Documentation\DocumentationRegistry;

class WikidocExtension extends AbstractBaseExtension
{
    public function getConfiguration(array $config, ContainerBuilder $container): WikidocConfiguration
    {
        return new WikidocConfiguration();
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

        // Injected straight into the service: setConfiguration() below
        // recurses into every array it meets, so the roots MAP (whose keys
        // are root names and whose values are arrays) would be flattened
        // into wikidoc.roots.app.path-style scalar parameters instead of one
        // array argument. Pulled out before that runs.
        $roots = $config['roots'] ?? [];
        unset($config['roots']);

        // %kernel.project_dir% and friends are resolved here rather than
        // left for the service to interpret - a root is a plain path by the
        // time the registry sees it.
        foreach ($roots as $name => $root) {
            $roots[$name] = [
                'path' => $container->resolveEnvPlaceholders($this->resolveParameters($container, (string) $root['path']), true),
                'label' => $root['label'] ?? ucfirst((string) $name),
            ];
        }

        $container->getDefinition(DocumentationRegistry::class)->setArgument('$roots', $roots);

        $this->setConfiguration($container, $config, $configuration->getTreeBuilder()->buildTree()->getName());
    }

    protected function resolveParameters(ContainerBuilder $container, string $value): string
    {
        return (string) $container->getParameterBag()->resolveValue($value);
    }
}
