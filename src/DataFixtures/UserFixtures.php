<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{

    public function load(ObjectManager $em)
    {
        $user = new User();
        $user->setUsername("root");
        $user->setPassword('$2y$13$Rr1qoIjS0E/u6mDPOT7gxeB/qcT0eoSC0yHFKaWBU0iVDkEQFtEFG');  
        $user->setRoles(array("ROLE_ADMIN"));      
        $em->persist($user);
        $em->flush();
    }
}
