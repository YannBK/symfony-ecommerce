<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW);
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
            TextField::new('firstname', 'Prénom')
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('lastname', 'Nom')
                ->setFormTypeOption('disabled', 'disabled'),
            DateTimeField::new('createdAt', 'Passée le')
                ->setFormTypeOption('disabled', 'disabled'),
            EmailField::new('email', 'Email')
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('subject', 'Sujet')
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('text', 'Contenu')
                ->setFormTypeOption('disabled', 'disabled'),
            BooleanField::new('answered', 'Répondu'),
        ];
    }
}
