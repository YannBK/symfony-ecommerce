<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
// use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class OrderCrudController extends AbstractCrudController
{
    // private $entityManager;
    // private $crudUrlGenerator;

    public function __construct(EntityManagerInterface $entityManager, AdminUrlGenerator $crudUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->crudUrlGenerator = $crudUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable(Action::NEW);
    }
    // public function configureActions(Actions $actions): Actions
    // {
    //     $updatePreparation = Action::new('updatePreparation', 'Préparation en cours', 'fas fa-truck-ramp-box')->linkToCrudAction('updatePreparation');
    //     $updateDelivery = Action::new('updateDelivery', 'Livraison en cours', 'fas fa-truck')->linkToCrudAction('updateDelivery');
    //     $updateDone = Action::new('updateDone', 'Livrée', 'fas fa-circle-check')->linkToCrudAction('updateDone');
    //     return $actions
    //         ->add('detail', $updatePreparation)
    //         ->add('detail', $updateDelivery)
    //         ->add('detail', $updateDone)
    //         ->add('index','detail');
    // }

    // public function updatePreparation(AdminContext $context)
    // {
    //     // dd($context);
    //     // echo(json_encode("update done"));
    //     // dd($context->getEntity());
    //     $order = $context->getEntity()->getInstance();
    //     $order->setState(2);
    //     $this->entityManager->flush();
        
    //     $this->addFlash('notice', "<span style='color:green;'><b>La commande ".$order->getReference()." est bien <u>en cours de préparation</u></b></span>");
        
    //     $url = $this->crudUrlGenerator
    //     ->setController(OrderCrudController::class)
    //     ->setAction('index')
    //     ->generateUrl();
        
    //     return $this->redirect($url);
    // }
    
    // public function updateDelivery(AdminContext $context)
    // {
    //     // dd($context);
    //     // echo(json_encode("update done"));
    //     // dd($context->getEntity());
    //     $order = $context->getEntity()->getInstance();
    //     // dd($order);
    //     $order->setState(3);
    //     $this->entityManager->flush();
        
    //     $this->addFlash('notice', "<span style='color:green;'><b>La commande ".$order->getReference()." est bien <u>en cours de livraison</u></b></span>");
        
    //     $url = $this->crudUrlGenerator
    //     ->setController(OrderCrudController::class)
    //     ->setAction('index')
    //     ->generateUrl();
        
    //     return $this->redirect($url);
    // }
    
    // public function updateDone(AdminContext $context)
    // {
    //     // dd($context);
    //     // echo(json_encode("update done"));
    //     // dd($context->getEntity());
    //     $order = $context->getEntity()->getInstance();
    //     // dd($order);
    //     $order->setState(4);
    //     $this->entityManager->flush();

    //     $this->addFlash('notice', "<span style='color:green;'><b>La commande ".$order->getReference()." est bien <u>livrée</u></b></span>");

    //     $url = $this->crudUrlGenerator
    //         ->setController(OrderCrudController::class)
    //         ->setAction('index')
    //         ->generateUrl();

    //     return $this->redirect($url);
    // }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setPageTitle('index', 'Les commandes')
        ->setPageTitle('edit', 'Modifier la commande')
        ->setDefaultSort(['id'=>'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('reference', 'Réf.')->setFormTypeOption('disabled', 'disabled'),
            DateTimeField::new('createdAt', 'Passée le')->setFormTypeOption('disabled', 'disabled'),
            TextField::new('user.getFullName', 'Client')->setFormTypeOption('disabled', 'disabled'),
            TextField::new('delivery', 'Adresse de livraison')->onlyOnDetail()->renderAsHtml()->setFormTypeOption('disabled', 'disabled'),
            MoneyField::new('total')->setCurrency('EUR')->setFormTypeOption('disabled', 'disabled'),
            TextField::new('carrierName', 'Transporteur')->setFormTypeOption('disabled', 'disabled'),
            MoneyField::new('carrierPrice', 'Frais de port')->setCurrency('EUR')->hideOnIndex()->setFormTypeOption('disabled', 'disabled'),
            ChoiceField::new('state', 'Etat')->setChoices([
                'Non payée' => 0,
                'Payée' => 1,
                'Préparation en cours' => 2,
                'Livraison en cours' => 3,
                'Livrée' => 4,
            ]),
            ArrayField::new('orderDetails', 'Produits achetés')->hideOnIndex()->setFormTypeOption('disabled', 'disabled')
        ];
    }
    
}
