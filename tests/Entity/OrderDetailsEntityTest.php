<?php

namespace App\Tests\Entity;

use App\Entity\OrderDetails;
use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OrderDetailsEntityTest extends KernelTestCase
{
    public function getEntity(): OrderDetails
    {
        return (new OrderDetails())
            ->setProductName("productnameetest")
            ->setQuantity(1)
            ->setPrice(1)
            ->setTotal(1)
            ->setProduct(null);
    }

    public function assertHasErrors(OrderDetails $code, int $expected)
    {
        self::bootKernel();
        $container = static::getContainer();
        $errors = $container->get('validator')->validate($code);
        $messages = [];

        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' ==> ' . $error->getMessage();
        }

        $this->assertEquals($expected, count($errors), implode(', ', $messages));
    }

    public function testValidOrderDetails()
    {
        $order = $this->getEntity()->setMyOrder(new Order());
        $this->assertHasErrors($order, 0);
    }
    
    public function testNullOrderOrderDetails()
    {
        $order = $this->getEntity();
        $this->assertHasErrors($order, 2);
    }
    
    public function testBlankProductNameOrderDetails()
    {
        $order = $this->getEntity()->setMyOrder(new Order());
        $this->assertHasErrors($order->setProductName(''), 1);
    }
    
    public function testBlankQuantityOrderDetails()
    {
        $order = (new OrderDetails())
        ->setProductName("productnameetest")
        ->setPrice(1)
        ->setTotal(1)
        ->setProduct(null)
        ->setMyOrder(new Order());
        $this->assertHasErrors($order, 1);
    }
    
    public function testBlankPriceOrderDetails()
    {
        $order = (new OrderDetails())
        ->setProductName("productnameetest")
        ->setQuantity(1)
        ->setTotal(1)
        ->setProduct(null)
        ->setMyOrder(new Order());
        $this->assertHasErrors($order, 1);
    }

    public function testBlankTotalOrderDetails()
    {
        $order =  (new OrderDetails())
            ->setProductName("productnameetest")
            ->setQuantity(1)
            ->setPrice(1)
            ->setProduct(null)
            ->setMyOrder(new Order());
        $this->assertHasErrors($order, 1);
    }
}
