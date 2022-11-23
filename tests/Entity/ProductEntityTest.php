<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductEntityTest extends KernelTestCase
{
    public function getEntity(): Product
    {
        return (new Product())
            ->setName("nametest")
            ->setSlug("slugtest")
            ->setIllustration("illustrationtest")
            ->setSubtitle("subtitletest")
            ->setDescription("descriptiontest");
    }

    public function assertHasErrors(Product $code, int $expected)
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

    public function testValidProduct()
    {
        $product = $this->getEntity()
            ->setPrice(1)
            ->setIsBest(true);
        $this->assertHasErrors($product, 0);
    }

    public function testBlankNameProduct()
    {
        $product = $this->getEntity()
            ->setName('')
            ->setPrice(1)
            ->setIsBest(true);
        $this->assertHasErrors($product, 1);
    }

    public function testBlankSlugProduct()
    {
        $product = $this->getEntity()
            ->setSlug('')
            ->setPrice(1)
            ->setIsBest(true);
        $this->assertHasErrors($product, 1);
    }

    public function testBlankIllustrationProduct()
    {
        $product = $this->getEntity()
            ->setIllustration('')
            ->setPrice(1)
            ->setIsBest(true);
        $this->assertHasErrors($product, 1);
    }

    public function testBlankSubtitleProduct()
    {
        $product = $this->getEntity()
            ->setSubtitle('')
            ->setPrice(1)
            ->setIsBest(true);
        $this->assertHasErrors($product, 1);
    }

    public function testBlankDescriptionProduct()
    {
        $product = $this->getEntity()
            ->setDescription('')
            ->setPrice(1)
            ->setIsBest(true);
        $this->assertHasErrors($product, 1);
    }

    public function testBlankPriceProduct()
    {
        $product = $this->getEntity()
            ->setIsBest(true);
        $this->assertHasErrors($product, 1);
    }

    public function testBlankIsBestProduct()
    {
        $product = $this->getEntity()
            ->setPrice(1);
        $this->assertHasErrors($product, 1);
    }
}
