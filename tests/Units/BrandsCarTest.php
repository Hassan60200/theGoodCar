<?php

namespace Units;

use App\Entity\BrandsCar;
use PHPUnit\Framework\TestCase;

class BrandsCarTest extends TestCase
{
    public function testBrandsCarIsCreated()
    {
        $brand = new BrandsCar();
        $brand->setName('Toyota')
            ->setSlug('toyota');

        $this->assertEquals('Toyota', $brand->getName());
        $this->assertEquals('toyota', $brand->getSlug());
    }
}
