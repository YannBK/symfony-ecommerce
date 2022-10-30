<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function(Action $action) {
                return $action->setLabel('Nouveau header');
            });
    }

    public function configureCrud(Crud $crud): Crud
    {
        // $name = $this->getNameOf();
        return $crud
            ->setPageTitle('index', 'Vos headers')
            ->setPageTitle('new', 'Nouveau header')
            ->setPageTitle('edit', 'Modifier le header');
            // ->setPageTitle('edit', $this->getNameOf());
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre du header'),
            TextareaField::new('content', 'Contenu du header'),
            TextField::new('btnTitle', 'Nom du bouton'),
            TextField::new('btnUrl', 'Url de destination du bouton'),
            ImageField::new('illustration')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired((false)),
        ];
    }
    
}
