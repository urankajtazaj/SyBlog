<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Post;
use App\Entity\Category;

class ArchiveController extends Controller
{

    protected $itemsPerPage = 16;

    private function createPagination($query, $page, int $itemsPerPage) {
        $paginator = $this->get('knp_paginator');

        return $paginator->paginate(
            $query,
            $page,
            $itemsPerPage
        );
    }

    /**
     * @Route("/archive", name="archive")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $posts_qb = $em->getRepository(Post::class)->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $page = $request->get('page') ? $request->get('page') : 1;

        $posts = $this->createPagination($posts_qb, $page, $this->itemsPerPage);

        return $this->render('archive/index.html.twig', [
            'controller_name' => 'ArchiveController',
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/archive/{category}", name="archive_cat")
     */
    public function post_category(Request $request, $category)
    {
        $em = $this->getDoctrine()->getManager();

        $cat = $em->getRepository(Category::class)->findOneBy(['name' => $category]);

        $posts_qb = $em->getRepository(Post::class)->createQueryBuilder('p')
            ->where(':category = p.category')
            ->setParameter('category', $cat)
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $page = $request->get('page') ? $request->get('page') : 1;
        $posts = $this->createPagination($posts_qb, $page, $this->itemsPerPage);

        return $this->render('archive/index.html.twig', [
            'category' => $category,
            'tag' => null,
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/archive/tags/{tag}", name="tag_single")
     */
    public function findTag(Request $request, $tag) {
        $em = $this->getDoctrine()->getManager();

        $posts_qb = $em->getRepository(Post::class)->createQueryBuilder('p')
            ->where('p.tags like :tag')
            ->setParameter(':tag', '%' . $tag . '%')
            ->orderBy('p.id', 'DESC')
            ->getQuery();
            
        $page = $request->get('page') ? $request->get('page') : 1;
        $posts = $this->createPagination($posts_qb, $page, $this->itemsPerPage);

        return $this->render('archive/index.html.twig', [
            'tag' => $tag,
            'category' => null,
            'posts' => $posts
        ]);
    }

}
