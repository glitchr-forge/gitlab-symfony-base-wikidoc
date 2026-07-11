<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

/*
 * This file is part of the Glitchr package.
 *
 * (c) Marco Meyer <marco.meyer@glitchr.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 * The three concrete document CRUD controllers ride the base-bundle-admin
 * stack; autoconfigured so its CrudControllerInterface autoconfiguration
 * (routes + menu) picks them up. The Abstract/ base class is excluded - it
 * has no getEntityFqcn() of its own and is never routed directly.
 */
return function (ContainerConfigurator $configurator) {

    $services = $configurator->services();
    $services->defaults()
        ->autowire(true)
        ->autoconfigure(true)
        ->public(false);

    if (class_exists('Base\\Admin\\Controller\\AbstractCrudController')) {
        $services->load('Base\\Wikidoc\\Controller\\Backend\\Crud\\', dirname(__DIR__) . '/src/Controller/Backend/Crud/')
            ->exclude(dirname(__DIR__) . '/src/Controller/Backend/Crud/Abstract')
            ->tag('controller.service_arguments');

        // the customer-facing help panel - not a CRUD controller, just tagged
        // for autowiring
        $services->set(\Base\Wikidoc\Controller\Backend\ManualController::class)
            ->tag('controller.service_arguments');
    }
};
