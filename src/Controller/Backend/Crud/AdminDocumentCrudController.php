<?php

namespace Base\Wikidoc\Controller\Backend\Crud;

use Base\Field\SelectField;
use Base\Wikidoc\Controller\Backend\Crud\Abstract\AbstractDocumentCrudController;
use Base\Wikidoc\Entity\AdminSection;

/**
 *
 */
class AdminDocumentCrudController extends AbstractDocumentCrudController
{
    public function configureFields(string $pageName, ...$args): iterable
    {
        return parent::configureFields($pageName, [
            'slug' => function () {
                
                yield SelectField::new('tags')->setColumns(6)->allowMultipleChoices(false)->setClass(AdminSection::class);
            },
        ], $args);
    }
}
