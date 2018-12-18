<?php 

namespace App\Service;

class SettingService {

    protected $em;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function get() {
        return $this->em->getRepository(\App\Entity\Settings::class)->find(1);
    }

    public function getMenu() {
        return $this->em->getRepository(\App\Entity\Menu::class)->findAll();
    }
}