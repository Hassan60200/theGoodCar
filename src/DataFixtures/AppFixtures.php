<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private \Faker\Generator $faker;

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
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

        $manager->flush();
    }
}
