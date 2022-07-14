<?php

namespace App\DataFixtures;

use App\Entity\MovieOwner;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    const TEST_EMAIL_COM = 'test@email.com';

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail(self::TEST_EMAIL_COM);
        $movieOwner = new MovieOwner();
        $manager->persist($movieOwner);
        $movieOwner->setIdentity($user);
        $manager->persist($user);
        $manager->flush();
    }
}
