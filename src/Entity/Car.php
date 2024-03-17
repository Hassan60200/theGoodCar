<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $yearOfManufacture = null;

    #[ORM\Column(length: 15)]
    private ?string $color = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 20)]
    private ?string $fuelType = null;

    #[ORM\Column]
    private ?int $mileage = null;

    #[ORM\Column(length: 255)]
    private ?string $img = null;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    #[ORM\JoinColumn(nullable: false)]
    private ?BrandsCar $brand = null;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ModelsCar $carModel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYearOfManufacture(): ?\DateTimeInterface
    {
        return $this->yearOfManufacture;
    }

    public function setYearOfManufacture(\DateTimeInterface $yearOfManufacture): static
    {
        $this->yearOfManufacture = $yearOfManufacture;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getFuelType(): ?string
    {
        return $this->fuelType;
    }

    public function setFuelType(string $fuelType): static
    {
        $this->fuelType = $fuelType;

        return $this;
    }

    public function getMileage(): ?int
    {
        return $this->mileage;
    }

    public function setMileage(int $mileage): static
    {
        $this->mileage = $mileage;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): static
    {
        $this->img = $img;

        return $this;
    }

    public function getBrand(): ?BrandsCar
    {
        return $this->brand;
    }

    public function setBrand(?BrandsCar $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getCarModel(): ?ModelsCar
    {
        return $this->carModel;
    }

    public function setCarModel(?ModelsCar $carModel): static
    {
        $this->carModel = $carModel;

        return $this;
    }
}
