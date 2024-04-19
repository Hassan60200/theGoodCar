<?php

namespace Units;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserIsCreated()
    {
        $user = new User();
        $user->setEmail('johndoe@gmail.com')
            ->setPassword('password')
            ->setRoles(['ROLE_USER'])
            ->setFirstname('John')
            ->setLastname('Doe')
            ->setIsVerified(false)
            ->setAge(25);

        $user->setCreatedAt(new \DateTime('now'));

        $this->assertEquals(25, $user->getAge());
        $this->assertEquals('John', $user->getFirstname());
        $this->assertEquals('Doe', $user->getLastname());
        $this->assertEquals('johndoe@gmail.com', $user->getEmail());
        $this->assertFalse($user->isVerified());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());

    }

    // test if user not created
    public function testUserEmptyFields()
    {
        $user = new User();
        $user->setEmail('')
            ->setPassword('')
            ->setRoles(['ROLE_USER'])
            ->setFirstname('John')
            ->setLastname('Doe')
            ->setIsVerified(false)
            ->setAge(25);

        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getPassword());

    }
}
