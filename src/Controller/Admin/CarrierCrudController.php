<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CarrierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Carrier::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function(Action $action) {
                return $action->setLabel('Nouveau transporteur');
            });
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Transporteurs')
            ->setPageTitle('new', 'Nouveau transporteur')
            ->setPageTitle('edit', 'Modifier le transporteur');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'nom'),
            TextareaField::new('description', 'description'),
            MoneyField::new('price', 'prix')->setCurrency('EUR'),
        ];
    }
    
}
