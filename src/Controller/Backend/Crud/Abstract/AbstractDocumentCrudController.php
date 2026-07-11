<?php

namespace Base\Wikidoc\Controller\Backend\Crud\Abstract;

use Base\Admin\Controller\AbstractCrudController;
use Base\Admin\Field\DiscriminatorField;
use Base\Admin\Field\EditorField;
use Base\Admin\Field\IconField;
use Base\Admin\Field\NumberField;
use Base\Admin\Field\SlugField;
use Base\Admin\Field\TextareaField;
use Base\Admin\Field\TextField;

class AbstractDocumentCrudController extends AbstractCrudController
{
    public static function getPreferredIcon(): ?string
    {
        return 'fa-solid fa-book';
    }

    public function configureFields(string $pageName): iterable
    {
        yield DiscriminatorField::new()->showLeaf(true);
        yield TextField::new('title')->setColumns(6);
        yield SlugField::new('slug')->setTargetFieldName('title')->setColumns(3);

        yield IconField::new('icon')->setColumns(3);
        yield NumberField::new('priority')->setColumns(3);
        yield TextareaField::new('excerpt')->setColumns(12);
        yield EditorField::new('content')->onlyOnForms();
    }
}
