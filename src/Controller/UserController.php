<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Post;
use App\Entity\User;
use App\Form\UserInfoForm;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(User::class);

        $user = $this->getUser();

        $posts = $repo->find($user->getId())->getPosts();

        $form = $this->createForm(UserInfoForm::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $em->flush();

        }

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'posts' => $posts,
            'form' => $form->createView()
        ]);
    }
}
