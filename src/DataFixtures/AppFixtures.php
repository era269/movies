<?php

namespace App\DataFixtures;

use App\Entity\MovieOwner;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    const TEST_EMAIL     = 'test@email.com';
    const TEST_EMAIL_SECOND = 'test-second@email.com';

    public function load(ObjectManager $manager): void
    {
        $this->addUser(self::TEST_EMAIL, $manager);
        $this->addUser(self::TEST_EMAIL_SECOND, $manager);
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    private function addUser(string $email, ObjectManager $manager): User
    {
        $user = new User();
        $user->setEmail($email);

        $movieOwner = new MovieOwner();
        $manager->persist($movieOwner);

        $movieOwner->setIdentity($user);
        $manager->persist($user);

        return $user;
    }
}
