<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Comment;

class CommentsController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    /**
     * @Route("/admin/comments", name="comments")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $comments_qb = $em->getRepository(Comment::class)
            ->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC')
            ->getQuery();

        $itemsPerPage = 10;
        $page = $request->get('page') ? $request->get('page') : 1;

        $paginator = $this->get('knp_paginator');

        $comments = $paginator->paginate(
            $comments_qb,
            $page,
            $itemsPerPage
        );

        return $this->render('comments/index.html.twig', [
            'controller_name' => 'CommentsController',
            'comments' => $comments,
            'current' => 'comments',
            'headline' => 'Comments'
        ]);
    }
}
