<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Post;
use App\Entity\Category;

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
        $em = $this->getDoctrine()->getManager();

        $cat = $em->getRepository(Category::class)->findOneBy(['name' => $category]);

        $posts_qb = $em->getRepository(Post::class)->createQueryBuilder('p')
            ->where('p.category = :category')
            ->setParameter('category', $cat)
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $posts = $posts_qb->setMaxResults(10)->execute();

        return $this->render('archive/index.html.twig', [
            'controller_name' => 'ArchiveController',
            'posts' => $posts
        ]);
    }

}