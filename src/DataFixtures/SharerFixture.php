<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Sharer;

class SharerFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $sharer = new Sharer();
        /**
         * @var App\Entity\Sharer;
         */
        $sharer->setName('facebook');
        $sharer->setIcon('fab fa-facebook-f');
        $sharer->setUrl('https://www.facebook.com/sharer/sharer.php?u=');

        $sharer_tw = new Sharer();
        $sharer_tw->setName('twitter');
        $sharer_tw->setIcon('fab fa-twitter');
        $sharer_tw->setUrl('https://twitter.com/home?status=');

        $sharer_in = new Sharer();
        $sharer_in->setName('linkedin');
        $sharer_in->setIcon('fab fa-linkedin-in');
        $sharer_in->setUrl('https://www.linkedin.com/shareArticle?mini=true&url=');

        $sharer_r = new Sharer();
        $sharer_r->setName('reddit');
        $sharer_r->setIcon('fab fa-reddit-alien');
        $sharer_r->setUrl('http://www.reddit.com/submit?url=');

        $manager->persist($sharer);
        $manager->flush();

        $manager->persist($sharer_tw);
        $manager->flush();

        $manager->persist($sharer_in);
        $manager->flush();

        $manager->persist($sharer_r);
        $manager->flush();
    }
}
