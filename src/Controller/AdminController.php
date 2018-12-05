<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;

use App\Entity\Post;

use App\Form\PostForm;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {

        $em = $this->getDoctrine()->getManager();
        
        $posts_qb = $em->getRepository(Post::class)->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $comments_qb = $em->getRepository(\App\Entity\Comment::class)->createQueryBuilder('c')
        ->orderBy('c.id', 'DESC')
        ->getQuery();

        $users_qb = $em->getRepository(\App\Entity\User::class)->createQueryBuilder('u')
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
        ]);
    }

    /**
     * @Route("/admin/posts", name="admin_posts")
     */
    public function posts() {
        $em = $this->getDoctrine()->getManager();
        $posts_qb = $em->getRepository(Post::class)->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $posts = $posts_qb->execute();

        return $this->render('admin/posts.html.twig', [
            'current' => 'posts',
            'posts' => $posts,
            'headline' => 'All posts'
        ]);
    }

    /**
     * @Route("/admin/post/edit/{id}", name="post_edit")
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
    
                if (!empty($prevFilename) && $prevFilename != null) {
                    unlink($this->getParameter('cover_folder') . '/' . $prevFilename);
                }

                $data->setCover($filename);
    
                $file->move(
                    $this->getParameter('cover_folder'),
                    $filename
                );
            } else {                
                if ($form->get('delete_cover')->getData()) {
                    $data->setCover(null);

                    if (!empty($prevFilename) && $prevFilename != null) {
                        unlink($this->getParameter('cover_folder') . '/' . $prevFilename);
                    }
                } else {
                    $data->setCover($prevFilename);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute("admin_posts");
        }

        return $this->render(
            "blog/post_new.html.twig",
            [
                'form' => $form->createView(),
                'post' => $post,
                'cover_img' => $prevFilename,
                'headline' => 'Edit Post',
                'id' => $id,
                'current' => 'posts'
            ]
        );

    }
}
