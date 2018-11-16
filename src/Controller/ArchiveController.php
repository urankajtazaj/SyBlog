<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Post;

class ArchiveController extends AbstractController
{
    /**
     * @Route("/archive", name="archive")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $posts_qb = $em->getRepository(Post::class)->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $posts = $posts_qb->setMaxResults(10)->execute();

        return $this->render('archive/index.html.twig', [
            'controller_name' => 'ArchiveController',
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/archive/{category}", name="archive_cat")
     */
    public function post_category($category)
    {
        return $this->render('archive/index.html.twig', [
            'controller_name' => 'ArchiveController',
        ]);
    }

}
