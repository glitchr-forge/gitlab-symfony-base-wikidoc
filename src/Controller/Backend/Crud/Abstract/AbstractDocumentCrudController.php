<?php

namespace Base\Wikidoc\Controller\Backend\Crud\Abstract;

use Base\Controller\Backend\AbstractCrudController;
use Base\Field\DiscriminatorField;
use Base\Field\EditorField;
use Base\Field\IconField;
use Base\Field\NumberField;
use Base\Field\SlugField;
use Base\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

/**
 *
 */
class AbstractDocumentCrudController extends AbstractCrudController
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
                yield DiscriminatorField::new()->showLeaf(true);
                yield TextField::new('title')->setColumns(6);
                yield SlugField::new('slug')->setTargetFieldName("title")->setColumns(3);
                
                yield IconField::new('icon')->setColumns(3);
                yield NumberField::new('priority')->setColumns(3);
                yield TextareaField::new('excerpt')->setColumns(12);
                yield EditorField::new('content')->onlyOnForms();
                
            },
        ], $args);
    }
}
