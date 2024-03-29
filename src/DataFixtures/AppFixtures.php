<?php

namespace App\DataFixtures;

use App\Entity\BrandsCar;
use App\Entity\ModelsCar;
use App\Entity\User;
use App\Repository\BrandsCarRepository;
use App\Repository\ModelsCarRepository;
use App\Trait\CarsTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AppFixtures extends Fixture
{
    use CarsTrait;

    private \Faker\Generator $faker;

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher,
                                private readonly EntityManagerInterface      $entityManager,
                                private readonly BrandsCarRepository         $brandsCarRepository,
                                private readonly ModelsCarRepository         $modelsCarRepository)
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $slugger = new AsciiSlugger();

        for ($i = 0; $i < 5; ++$i) {
            $user = new User();
            $user->setLastName($this->faker->lastName)
                ->setEmail($this->faker->email)
                ->setFirstName($this->faker->firstName)
                ->setAge($this->faker->numberBetween(18, 100))
                ->setPassword($this->passwordHasher->hashPassword($user, 'adminPassword'))
                ->setRoles(['ROLE_ADMIN'])
                ->setCreatedAt(new \DateTimeImmutable('now'));
            $manager->persist($user);
        }

        $brands = $this->getAllBrands();
        foreach ($brands as $brand) {
            $newBrand = new BrandsCar();
            $newBrand->setName($brand)
                ->setSlug($slugger->slug($brand));

            $manager->persist($newBrand);
        }
        $manager->flush();

        $models = $this->getAllModels();
        foreach ($models as $car) {
            $brand = $this->brandsCarRepository->findOneBy(['name' => $car['brand']]);
            foreach ($car['model'] as $model) {
                $modelCar = $this->modelsCarRepository->findOneBy(['name' => $model]);
                if (!$modelCar) {
                    $modelCar = new ModelsCar();
                    $modelCar->setName($model)
                        ->setSlug($slugger->slug($model))
                        ->setBrand($brand);
                    $this->entityManager->persist($modelCar);
                }
            }
        }

        $manager->flush();
    }
}
