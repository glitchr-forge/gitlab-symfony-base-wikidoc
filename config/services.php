<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Base\Wikidoc\Controller\Backend\ManualController;
use Base\Wikidoc\Documentation\DocumentationRegistry;
use Base\Wikidoc\Documentation\MarkdownRenderer;
use Base\Wikidoc\Documentation\SearchIndexBuilder;

/*
 * This file is part of the Glitchr package.
 *
 * (c) Marco Meyer <marco.meyer@glitchr.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 * The manual is filesystem-backed: markdown files under the configured
 * documentation roots, no entities and no CRUD. The roots argument is
 * replaced by WikidocExtension from the processed `wikidoc.roots` config.
 */
return function (ContainerConfigurator $configurator) {

    $services = $configurator->services();
    $services->defaults()
        ->autowire(false)
        ->autoconfigure(false)
        ->public(false);

    $services->set(DocumentationRegistry::class)
        ->arg('$roots', []);

    $services->set(MarkdownRenderer::class);

    $services->set(SearchIndexBuilder::class)
        ->args([
            service(DocumentationRegistry::class),
            service(MarkdownRenderer::class),
            // Optional: without a pool the index is simply recomputed, which
            // is fine for a handful of files and is what happens in tests.
            service('cache.app')->nullOnInvalid(),
        ]);

    // Same manual container wiring base-bundle-admin's own controllers need:
    // autoconfigure(false) is set file-wide, so the #[Required] setContainer()
    // setter AbstractController relies on is never applied automatically, and
    // the controller 500s the moment it calls render()/createNotFoundException().
    // A service_locator (not a raw service_container reference) is the
    // supported way to reach the private services listed in
    // AbstractController::getSubscribedServices().
    $controllerServiceLocator = service_locator([
        'router' => service('router')->nullOnInvalid(),
        'request_stack' => service('request_stack')->nullOnInvalid(),
        'http_kernel' => service('http_kernel')->nullOnInvalid(),
        'serializer' => service('serializer')->nullOnInvalid(),
        'security.authorization_checker' => service('security.authorization_checker')->nullOnInvalid(),
        'twig' => service('twig')->nullOnInvalid(),
        'form.factory' => service('form.factory')->nullOnInvalid(),
        'security.token_storage' => service('security.token_storage')->nullOnInvalid(),
        'security.csrf.token_manager' => service('security.csrf.token_manager')->nullOnInvalid(),
        'parameter_bag' => service('parameter_bag')->nullOnInvalid(),
        'web_link.http_header_serializer' => service('web_link.http_header_serializer')->nullOnInvalid(),
    ]);

    if (class_exists('Base\\Admin\\Context\\AdminContext')) {
        $services->set(ManualController::class)
            ->args([
                service('Base\\Admin\\Context\\AdminContext'),
                service('Base\\Admin\\Menu\\MenuBuilder'),
                service(DocumentationRegistry::class),
                service(MarkdownRenderer::class),
                service(SearchIndexBuilder::class),
            ])
            ->call('setContainer', [$controllerServiceLocator])
            ->public(true)
            ->tag('controller.service_arguments');
    }
};
