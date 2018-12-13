<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Post;
use App\Entity\User;
use App\Form\UserInfoForm;

class UserController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Post::class);

        $user = $this->getUser();

        $posts_qb = $repo->createQueryBuilder('p')
            ->where('p.user = :user')
            ->setParameter('user', $user->getId())
            ->getQuery();

        $posts = $posts_qb->execute();

        $form = $this->createForm(UserInfoForm::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em->flush();
        }

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'posts' => $posts,
            'form' => $form->createView(),
            'title' => 'User'
        ]);
    }

    /**
     * @Route("/admin/users", name="users")
     */
    public function list_users(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();

        $page = $request->get('page') ? $request->get('page') : 1;
        $paginator = $this->get('knp_paginator');

        $users = $paginator->paginate(
            $users,
            $page,
            10
        );

        return $this->render(
            'user/list.html.twig', [
                'users' => $users,
                'current' => 'users'
            ]
        );
    }
}
