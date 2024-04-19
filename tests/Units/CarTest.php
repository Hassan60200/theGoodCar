<?php

namespace Units;

use App\Entity\BrandsCar;
use App\Entity\Car;
use App\Entity\ModelsCar;
use PHPUnit\Framework\TestCase;

class CarTest extends TestCase
{
    // test if car is created
    public function testCarIsCreated()
    {
        $brand = new BrandsCar();
        $brand->setName('Toyota');

        $model = new ModelsCar();
        $model->setName('Corolla');

        $slugger = $brand->getName().'-'.$model->getName();

        $car = new Car($slugger);
        $car->setBrand($brand);
        $car->setCarModel($model);
        $car->setYearOfManufacture(2020);
        $car->setPrice(15000)
            ->setMileage(10000)
            ->setFuelType('Essence')
            ->setStatus('Vente')
            ->setCreatedAt(new \DateTime('now'));

        $this->assertEquals('Toyota', $car->getBrand());
        $this->assertEquals('Corolla', $car->getCarModel());
        $this->assertEquals(2020, $car->getYearOfManufacture());
        $this->assertEquals(15000, $car->getPrice());
        $this->assertEquals(10000, $car->getMileage());
        $this->assertEquals('Essence', $car->getFuelType());
        $this->assertEquals('Vente', $car->getStatus());

        $this->assertNotNull($car->getCreatedAt());
    }
}
