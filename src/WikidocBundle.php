<?php

namespace Base\Wikidoc;

use Base\Bundle\AbstractBaseBundle;
use Base\Traits\SingletonTrait;

class WikidocBundle extends AbstractBaseBundle
{
    // Gives this bundle its own singleton storage instead of sharing
    // AbstractBaseBundle's - see that class's constructor for why this is
    // required on every concrete bundle extending it, not just this one.
    use SingletonTrait;

    // The trait's own protected no-op __construct() (there to force
    // singleton access through getInstance()) takes priority over the
    // INHERITED AbstractBaseBundle::__construct() the moment the trait is
    // used directly here, which both hides its real registration logic and
    // makes Symfony's `new WikidocBundle()` in bundles.php fatal (protected
    // constructor). Re-declaring it explicitly, public, delegating to
    // parent, restores both.
    public function __construct()
    {
        parent::__construct();
    }

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
