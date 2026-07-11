<?php

namespace Base\Wikidoc\Controller\Backend\Crud;

use Base\Admin\Field\SelectField;
use Base\Wikidoc\Controller\Backend\Crud\Abstract\AbstractDocumentCrudController;
use Base\Wikidoc\Entity\AdminDocument;

class AdminDocumentCrudController extends AbstractDocumentCrudController
{
    public static function getEntityFqcn(): string
    {
        return AdminDocument::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield from parent::configureFields($pageName);
        yield SelectField::new('parent')->setColumns(6)->allowMultipleChoices(false)->setRequired(false)->setClass(AdminDocument::class);
    }
}
