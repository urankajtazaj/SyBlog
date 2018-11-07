<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Entity\Post;
use App\Entity\User;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(UserInterface $user)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(User::class);

        $posts = $repo->find($user->getId())->getPosts();

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'posts' => $posts
        ]);
    }
}
