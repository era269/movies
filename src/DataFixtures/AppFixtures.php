<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    const TEST_EMAIL        = 'test@email.com';
    const TEST_EMAIL_SECOND = 'test-second@email.com';

    public function load(ObjectManager $manager): void
    {
        $manager->persist(new User(self::TEST_EMAIL));
        $manager->persist(new User(self::TEST_EMAIL_SECOND));
        $manager->flush();
    }
}
