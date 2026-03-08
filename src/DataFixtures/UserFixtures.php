<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
        //
    }

    #[\Override]
    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('user1@example.com');
        $user1->setName('User One');
        $user1->setRoles(['ROLE_USER']);
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'password123'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('user2@example.com');
        $user2->setName('User Two');
        $user2->setRoles(['ROLE_ADMIN']);
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'password123'));
        $manager->persist($user2);

        $manager->flush();
    }
}
