<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        
        $user = new User();
        $user->setName("Uran Kajtazaj");
        $user->setUsername("root");
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword( password_hash('root', PASSWORD_BCRYPT) );

        $manager->persist($user);
        $manager->flush();
    }
}
