<?php

namespace Base\Wikidoc\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

use Base\Bundle\AbstractBaseConfiguration;

class WikidocConfiguration extends AbstractBaseConfiguration
{
    /**
     * getTreeBuilder() memoises the builder while this method ADDS children
     * to it, so a second call would redeclare them - see the same guard on
     * Base\Admin's configuration.
     */
    private bool $childrenDeclared = false;

    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = $this->getTreeBuilder();
        if ($this->childrenDeclared) {
            return $treeBuilder;
        }
        $this->childrenDeclared = true;

        $treeBuilder->getRootNode()
            ->children()
                // Documentation roots, LOWEST priority first. A page is
                // identified by its path within a root, so a later root
                // providing the same path replaces the earlier one - the
                // same "drop a file at the same path" override rule Symfony
                // already uses for bundle templates.
                //
                //     wikidoc:
                //         roots:
                //             base: { path: '%kernel.project_dir%/vendor/glitchr/base-bundle/docs', label: 'Base' }
                //             app:  { path: '%kernel.project_dir%/docs', label: 'Application' }
                //
                // A root that does not exist on disk is skipped, so shipping
                // this config before writing any docs is harmless.
                ->arrayNode('roots')
                    ->info('Documentation roots, lowest priority first; a later root overrides same-path pages.')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('path')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('label')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
