<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Create an admin user
        $admin = new User();
        $admin->setEmail('dark-shadow-95@windowslive.com');
        $admin->setUsername('Philip');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'klausbotler1'));
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        // Create a regular user
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setUsername('UserTest');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'userpass123'));
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);

        $manager->flush();
    }
}
