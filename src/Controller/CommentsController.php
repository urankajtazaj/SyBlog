<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Comment;

class CommentsController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="comments")
     */
    public function index()
    {

        $em = $this->getDoctrine()->getManager();

        $comments_qb = $em->getRepository(Comment::class)
            ->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC')
            ->getQuery();

        $comments = $comments_qb->execute();

        return $this->render('comments/index.html.twig', [
            'controller_name' => 'CommentsController',
            'comments' => $comments,
            'current' => 'comments',
            'headline' => 'Comments'
        ]);
    }
}
