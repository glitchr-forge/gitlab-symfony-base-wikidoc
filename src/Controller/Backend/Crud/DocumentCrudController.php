<?php

namespace Base\Wikidoc\Controller\Backend\Crud;

use Base\Controller\Backend\AbstractCrudController;
use Base\Field\DiscriminatorField;
use Base\Field\EditorField;

/**
 *
 */
class DocumentCrudController extends AbstractCrudController
{
    public static function getPreferredIcon(): ?string
    {
        return null;
    }

    public function configureFields(string $pageName, ...$args): iterable
    {
        $this->hideId();

        return parent::configureFields($pageName, [
            'id' => function () {
                yield DiscriminatorField::new()->showLeaf(false)->showIconOnly();
                yield EditorField::new('content');
            },
        ], $args);
    }
}
