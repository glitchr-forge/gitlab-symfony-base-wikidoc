<?php

namespace Base\Wikidoc\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

use Base\Bundle\AbstractBaseConfiguration;

/**
 *
 */
class WikidocConfiguration extends AbstractBaseConfiguration
{
    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        return $this->getTreeBuilder();
    }
}
