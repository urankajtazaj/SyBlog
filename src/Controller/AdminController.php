<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;

use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\User;
use App\Service\SettingService;
use App\Form\PostForm;

class AdminController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{

    protected $itemsPerPage = 10;

    /**
     * @Route("/admin", name="admin")
     */
    public function index(SettingService $setting)
    {

        $em = $this->getDoctrine()->getManager();
        
        $posts_qb = $em->getRepository(Post::class)->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $comments_qb = $em->getRepository(Comment::class)->createQueryBuilder('c')
        ->orderBy('c.id', 'DESC')
        ->getQuery();

        $users_qb = $em->getRepository(User::class)->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC')
            ->getQuery();

        $posts = $posts_qb->execute();
        $comments = $comments_qb->execute();
        $users = $users_qb->execute();

        return $this->render('admin/index.html.twig', [
            'current' => 'admin',
            'headline' => 'Dashboard',
            'posts' => $posts,
            'comments' => $comments,
            'users' => $users,
            'base' => $setting->get()
        ]);
    }

    /**
     * @Route("/admin/posts", name="admin_posts")
     */
    public function posts(Request $request, SettingService $setting) {
        $em = $this->getDoctrine()->getManager();

        $page = $request->get('page') ? $request->get('page') : 1;

        $posts_qb = $em->getRepository(Post::class)->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $paginator = $this->get('knp_paginator');

        $posts = $paginator->paginate(
            $posts_qb,
            $page,
            $this->itemsPerPage
        );

        // dd($posts);

        return $this->render('admin/posts.html.twig', [
            'current' => 'posts',
            'posts' => $posts,
            'headline' => 'All posts',
            'base' => $setting->get()
        ]);
    }
}
