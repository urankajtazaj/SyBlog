<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Post;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * @Route("/posts", name="post_list")
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
    public function post_new(Request $request) {
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
     * @Route("/post/edit/{slug}", name="post_edit")
     */
    public function post_edit(Request $request, $slug) {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->findOneBy(["title" => str_replace('-', ' ', $slug)]);

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

                return $this->redirectToRoute("post_single", ["slug" => str_replace(' ', '-', $data->getTitle())]);
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
     * @Route("/post/{slug}", name="post_single")
     */
    public function post_single($slug) {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->findOneBy(["title" => str_replace('-', ' ', $slug)]);

        return $this->render(
            "blog/post_single.html.twig",
            [
                'post' => $post
            ]
        );

    }
}
