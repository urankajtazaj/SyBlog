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
            ->where(':category = p.category')
            ->setParameter('category', $cat)
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $posts = $posts_qb->setMaxResults(10)->execute();

        return $this->render('archive/index.html.twig', [
            'category' => $category,
            'tag' => null,
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/archive/tags/{tag}", name="tag_single")
     */
    public function findTag($tag) {
        $em = $this->getDoctrine()->getManager();

        $posts_qb = $em->getRepository(Post::class)->createQueryBuilder('p')
            ->where('p.tags like :tag')
            ->setParameter(':tag', '%' . $tag . '%')
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $posts = $posts_qb->execute();

        return $this->render('archive/index.html.twig', [
            'tag' => $tag,
            'category' => null,
            'posts' => $posts
        ]);
    }

}
