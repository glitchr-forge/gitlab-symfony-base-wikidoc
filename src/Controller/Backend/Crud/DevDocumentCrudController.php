<?php

namespace Base\Wikidoc\Controller\Backend\Crud;

use Base\Field\SelectField;
use Base\Wikidoc\Controller\Backend\Crud\Abstract\AbstractDocumentCrudController;
use Base\Wikidoc\Entity\DevDocument;
use Base\Wikidoc\Entity\DevSection;

/**
 *
 */
class DevDocumentCrudController extends AbstractDocumentCrudController
{
    public function configureFields(string $pageName, ...$args): iterable
    {
        return parent::configureFields($pageName, [
            'slug' => function () {
                
                yield SelectField::new('parent')->setColumns(6)->setRequired(false)->allowMultipleChoices(false)->setClass(DevDocument::class);
            },
        ], $args);
    }
}
