<?php

namespace App\Controller\Admin;

use App\Entity\Marketing;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class MarketingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Marketing::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function(Action $action) {
                return $action->setLabel('Nouvelle accroche');
            });
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Vos accroches marketing')
            ->setPageTitle('new', 'Nouvelle accroche marketing')
            ->setPageTitle('edit', 'Modifier l\'accroche marketing');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre principal'),
            TextField::new('subtitle', 'Titre secondaire'),
            TextareaField::new('content', 'Contenu'),
            ImageField::new('illustration', 'Image')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            ChoiceField::new('place', 'Position sur la page')
                ->setChoices([
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ]),
            ChoiceField::new('imageSide', 'Position de l\'image')
                ->setChoices([
                    'Gauche' => 'left',
                    'Droite' => 'right',
                ]),
        ];
    }
}
