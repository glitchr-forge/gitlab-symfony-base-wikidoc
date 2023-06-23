<?php

namespace Base\Wikidoc\Controller\Backend\Crud;

use Base\Controller\Backend\AbstractCrudController;
use Base\Field\DiscriminatorField;
use Base\Field\EditorField;
use Base\Field\IconField;
use Base\Field\SelectField;
use Base\Field\SlugField;
use Base\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

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
                yield TextField::new('title')->setColumns(6);
                yield SlugField::new('slug')->setTargetFieldName("title")->setColumns(3);
                
                yield SelectField::new('tags')->setColumns(6)->allowMultipleChoices(false)->setClass(\Base\Wikidoc\Entity\Section::class);
                yield IconField::new('icon')->setColumns(3);
                yield TextareaField::new('excerpt')->setColumns(12);
                yield EditorField::new('content')->onlyOnForms();
            },
        ], $args);
    }
}
