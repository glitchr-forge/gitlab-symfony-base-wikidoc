<?php

namespace Base\Wikidoc\Controller\Backend\Crud;

use Base\Admin\Field\SelectField;
use Base\Wikidoc\Controller\Backend\Crud\Abstract\AbstractDocumentCrudController;
use Base\Wikidoc\Entity\DevDocument;

class DevDocumentCrudController extends AbstractDocumentCrudController
{
    public static function getEntityFqcn(): string
    {
        return DevDocument::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield from parent::configureFields($pageName);
        yield SelectField::new('parent')->setColumns(6)->setRequired(false)->allowMultipleChoices(false)->setClass(DevDocument::class);
    }
}
