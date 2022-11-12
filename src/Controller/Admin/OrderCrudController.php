<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW)
            ->disable(Action::DELETE);
    }
      
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Les commandes')
            ->setPageTitle('edit', 'Modifier la commande')
            ->setDefaultSort(['createdAt'=>'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('reference', 'Réf.')
                ->setFormTypeOption('disabled', 'disabled'),
            DateTimeField::new('createdAt', 'Passée le')
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('user.getFullName', 'Client')
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('delivery', 'Adresse de livraison')
                ->onlyOnDetail()
                ->renderAsHtml()
                ->setFormTypeOption('disabled', 'disabled'),
            MoneyField::new('total')
                ->setCurrency('EUR')
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('carrierName', 'Transporteur')
                ->setFormTypeOption('disabled', 'disabled'),
            MoneyField::new('carrierPrice', 'Frais de port')
                ->setCurrency('EUR')
                ->hideOnIndex()
                ->setFormTypeOption('disabled', 'disabled'),
            ChoiceField::new('state', 'Etat')
                ->setChoices([
                    'Non payée' => 0,
                    'Payée' => 1,
                    'Préparation en cours' => 2,
                    'Livraison en cours' => 3,
                    'Livrée' => 4,
                ]),
            ArrayField::new('orderDetails', 'Produits achetés')
                ->hideOnIndex()
                ->setFormTypeOption('disabled', 'disabled')
        ];
    }
}
