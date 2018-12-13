<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SettingFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $setting = new \App\Entity\Settings();
        $setting->setPageName("Blog Page");
        $setting->setEmail("blog@example.com");
        $setting->setTagline("Another Symfony Blog site");

        $manager->persist($setting);
        $manager->flush();
    }
}
