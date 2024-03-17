<?php

namespace App\Entity;

use App\Repository\BrandsCarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BrandsCarRepository::class)]
class BrandsCar extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Car::class, mappedBy: 'brand', orphanRemoval: true)]
    private Collection $cars;

    #[ORM\OneToMany(targetEntity: ModelsCar::class, mappedBy: 'brand', orphanRemoval: true)]
    private Collection $modelsCars;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
        $this->modelsCars = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Car>
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): static
    {
        if (!$this->cars->contains($car)) {
            $this->cars->add($car);
            $car->setBrand($this);
        }

        return $this;
    }

    public function removeCar(Car $car): static
    {
        if ($this->cars->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getBrand() === $this) {
                $car->setBrand(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ModelsCar>
     */
    public function getModelsCars(): Collection
    {
        return $this->modelsCars;
    }

    public function addModelsCar(ModelsCar $modelsCar): static
    {
        if (!$this->modelsCars->contains($modelsCar)) {
            $this->modelsCars->add($modelsCar);
            $modelsCar->setBrand($this);
        }

        return $this;
    }

    public function removeModelsCar(ModelsCar $modelsCar): static
    {
        if ($this->modelsCars->removeElement($modelsCar)) {
            // set the owning side to null (unless already changed)
            if ($modelsCar->getBrand() === $this) {
                $modelsCar->setBrand(null);
            }
        }

        return $this;
    }
}
