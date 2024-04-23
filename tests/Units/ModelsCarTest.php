<?php

namespace Units;

use App\Entity\BrandsCar;
use App\Entity\ModelsCar;
use PHPUnit\Framework\TestCase;

class ModelsCarTest extends TestCase
{
    public function testModelsCarIsCreated()
    {
        $brand = new BrandsCar();
        $brand->setName('Toyota')
            ->setSlug('toyota');

        $model = new ModelsCar();
        $model->setName('Corolla')
            ->setBrand($brand)
            ->setSlug('corolla');

        $this->assertEquals('Corolla', $model->getName());
        $this->assertEquals('Toyota', $model->getBrand()->getName());
    }

    public function testModelsCarWrongBrand()
    {
        $brand = new BrandsCar();
        $brand->setName('Toyota')
            ->setSlug('toyota');

        $model = new ModelsCar();
        $model->setName('Corolla')
            ->setBrand($brand)
            ->setSlug('corolla');

        $this->assertNotEquals('Peugeot', $model->getName());
    }
}
