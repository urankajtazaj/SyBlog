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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\File\File;

use App\Entity\Post;
use App\Entity\Category;
use App\Form\SearchForm;
use App\Form\PostForm;

class BlogController extends AbstractController
{

    /**
     * @Route("/", name="post_list")
     */
    public function post_list(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository(Post::class)->findAll();

        $searchForm = [];

        $form = $this->createForm(SearchForm::class, $searchForm);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $qb = $em->getRepository(Post::class)->createQueryBuilder('p')
                                                            ->where('p.title like :title or p.content like :content')
                                                            ->setParameter('title', '%' . $data['query'] . '%')
                                                            ->setParameter('content', '%' . $data['query'] . '%')
                                                            ->orderBy('p.id', 'DESC')
                                                            ->getQuery();
                                            
            $filteredPosts = $qb->execute();

            return $this->render(
                "blog/post_list.html.twig",
                [
                    'posts' => $filteredPosts,
                    'form' => $form->createView()
                ]
            );
        }

        return $this->render(
            "blog/post_list.html.twig",
            [
                'posts' => $posts,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/posts/new", name="post_new")
     */
    public function post_new(Request $request, UserInterface $user) {
        $post = new Post();

        $form = $this->createForm(PostForm::class, $post, ['cats' => $this->getDoctrine()->getManager()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $file = $post->getCover();
            $filename = md5(uniqid()) . "." . $file->getExtension();

            $data->setUser($user);
            $data->setViewCount(0);
            $data->setDateCreated(new \DateTime("now"));
            $data->setCover($filename);

            $file->move(
                $this->getParameter('cover_folder'),
                $filename
            );
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();

            return $this->redirectToRoute("post_list");
        }

        return $this->render(
            "blog/post_new.html.twig",
            [
                'form' => $form->createView(),
                'headline' => 'New post'
            ]
        );
    }

    /**
     * @Route("/post/edit/{id}", name="post_edit")
     */
    public function post_edit(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->find($id);

        $prevFilename = $post->getCover();

        if ($post->getCover()) {
            $post->setCover(
                new File($this->getParameter('cover_folder') . '/' . $post->getCover())
            );
        }

        $form = $this->createForm(PostForm::class, $post, ['cats' => $this->getDoctrine()->getManager()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            if ($data->getCover()) {
                $file = $post->getCover();
                $filename = md5(uniqid()) . "." . $file->guessExtension();
    
                $data->setCover($filename);
    
                $file->move(
                    $this->getParameter('cover_folder'),
                    $filename
                );
            } else {
                $data->setCover($prevFilename);
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute("post_single", ['id' => $data->getId()]);
        }

        return $this->render(
            "blog/post_new.html.twig",
            [
                'form' => $form->createView(),
                [
                    'post' => $post,
                ],
                'headline' => 'Edit post'
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

        // Update view count
        $post->setViewCount($post->getViewCount() + 1);
        $em->flush();

        $qb = $em->getRepository(Post::class)->createQueryBuilder('p')
                                                    ->andWhere('p.id != :id')
                                                    ->setParameter('id', $id)
                                                    ->orderBy('p.id', 'DESC')
                                                    ->getQuery();

        $all_posts = $qb->setMaxResults(5)->execute();

        return $this->render(
            "blog/post_single.html.twig",
            [
                'post' => $post,
                'posts' => $all_posts
            ]
        );
        
    }
}
