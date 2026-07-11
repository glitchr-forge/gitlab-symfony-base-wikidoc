<?php

namespace Base\Wikidoc;

use Base\Bundle\AbstractBaseBundle;

class WikidocBundle extends AbstractBaseBundle
{
    /**
     * Modern bundle layout: the class lives in src/, the bundle root is the
     * package root - so TwigBundle picks up ./templates as @Wikidoc (see
     * the identical fix on Base\Admin\AdminBundle).
     */
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
