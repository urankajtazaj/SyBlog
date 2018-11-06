<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Entity\Post;

class BlogController extends AbstractController
{

    /**
     * @Route("/", name="post_list")
     */
    public function post_list() {

        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository(Post::class)->findAll();

        return $this->render(
            "blog/post_list.html.twig",
            [
                'posts' => $posts
            ]
        );
    }

    /**
     * @Route("/posts/new", name="post_new")
     */
    public function post_new(Request $request, UserInterface $user) {
        $post = new Post();

        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'rows' => 10
                ]
            ])
            ->add('author', HiddenType::class, [
                'data' => $user->getId()
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $data = $form->getData();
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($data);
                $em->flush();

                return $this->redirectToRoute("post_list");
            }

        return $this->render(
            "blog/post_new.html.twig",
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/post/edit/{id}", name="post_edit")
     */
    public function post_edit(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->find($id);

        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'rows' => 10
                ]
            ])
            ->add('author', HiddenType::class)
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $data = $form->getData();
                
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                return $this->redirectToRoute("post_single", ['id' => $data->getId()]);
            }

        return $this->render(
            "blog/post_new.html.twig",
            [
                'form' => $form->createView(),
                [
                    'post' => $post
                ]
            ]
        );

    }

    /**
     * @Route("/post/delete/{id}", name="delete")
     */
    public function post_delete($id) {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->find($id);

        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute("post_list");
    }

    /**
     * @Route("/post/{id}", name="post_single")
     */
    public function post_single($id) {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->find($id);

        return $this->render(
            "blog/post_single.html.twig",
            [
                'post' => $post
            ]
        );

    }
}
