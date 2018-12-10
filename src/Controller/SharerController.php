<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Sharer;

class SharerController extends AbstractController
{
    /**
     * @Route("/admin/sharer", name="sharer_list")
     */
    public function index()
    {

        $repo = $this->getDoctrine()->getManager()->getRepository(Sharer::class);

        /**
         * @var App\Entity\Sharer;
         */
        $sharer = $repo->findAll();

        return $this->render('sharer_list.html.twig', [
            'links' => $sharer
        ]);
    }

    /**
     * @Route("/admin/sharer/add", name="sharer_add")
     */
    public function add()
    {

        $em = $this->getDoctrine()->getManager();

        /**
         * @var App\Entity\Sharer;
         */
        $sharer = new Sharer();

        return $this->render('sharer_list.html.twig', [
            'links' => $sharer
        ]);
    }

}
