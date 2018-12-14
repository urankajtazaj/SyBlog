<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Service\SettingService;

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
    public function index(Request $request, SettingService $setting)
    {
        $em = $this->getDoctrine()->getManager();
        $posts_qb = $em->getRepository(Post::class)->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $page = $request->get('page') ? $request->get('page') : 1;

        $posts = $this->createPagination($posts_qb, $page, $this->itemsPerPage);

        return $this->render('archive/index.html.twig', [
            'controller_name' => 'ArchiveController',
            'posts' => $posts,
            'base' => $setting->get()
        ]);
    }

    /**
     * @Route("/archive/{category}", name="archive_cat")
     */
    public function post_category(Request $request, SettingService $setting, $category)
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
            'posts' => $posts,
            'base' => $setting->get()
        ]);
    }

    /**
     * @Route("/archive/tags/{tag}", name="tag_single")
     */
    public function findTag(Request $request, SettingService $setting, $tag) {
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
            'posts' => $posts,
            'base' => $setting->get()
        ]);
    }

}
