<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Sharer;

class SharerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $fb = new Sharer();
        $fb->setName("Facebook");
        $fb->setIcon("fab fa-facebook-f");
        $fb->setUrl("https://www.facebook.com/sharer/sharer.php?u=");

        $t = new Sharer();
        $t->setName("Twitter");
        $t->setIcon("fab fa-twitter");
        $t->setUrl("https://twitter.com/home?status=");

        $in = new Sharer();
        $in->setName("LinkedIn");
        $in->setIcon("fab fa-linkedin-in");
        $in->setUrl("https://www.linkedin.com/shareArticle?mini=true&url=");

        $red = new Sharer();
        $red->setName("Reddit");
        $red->setIcon("fab fa-reddit-alien");
        $red->setUrl("http://www.reddit.com/submit?url=");

        $manager->persist($fb);
        $manager->flush();

        $manager->persist($t);
        $manager->flush();

        $manager->persist($in);
        $manager->flush();

        $manager->persist($red);
        $manager->flush();
    }
}
