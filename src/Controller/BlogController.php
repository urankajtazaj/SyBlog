<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;

use Doctrine\ORM\Query;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Category;
use App\Form\SearchForm;
use App\Form\PostForm;
use App\Form\CommentForm;
use App\Entity\Sharer;
use App\Entity\Settings;

use App\Service\SettingService;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="post_list")
     */
    public function post_list(Request $request, SettingService $setting) {

        $em = $this->getDoctrine()->getManager();
        $posts_qb = $em->getRepository(Post::class)
            ->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $popular_qb = $em->getRepository(Post::class)
            ->createQueryBuilder('p')
            ->orderBy('p.view_count', 'DESC')
            ->getQuery();

        $comment_qb = $em->getRepository(Comment::class)
            ->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC')
            ->getQuery();

        $posts = $posts_qb->setMaxResults(10)->execute();
        $popular_posts = $popular_qb->setMaxResults(7)->execute();
        $comments = $comment_qb->setMaxResults(5)->execute();

        $categories = $em->getRepository(Category::class)->findAll();

        $searchForm = [];

        $form = $this->createForm(SearchForm::class, $searchForm);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $qb = $em->getRepository(Post::class)
                ->createQueryBuilder('p')
                ->where('p.title like :title or p.content like :content')
                ->setParameter('title', '%' . $data['query'] . '%')
                ->setParameter('content', '%' . $data['query'] . '%')
                ->orderBy('p.id', 'DESC')
                ->getQuery();
                                            
            $posts = $qb->execute();
        }

        return $this->render(
            "blog/post_list.html.twig",
            [
                'posts' => $posts,
                'popular' => $popular_posts,
                'comments' => $comments,
                'form' => $form->createView(),
                'base' => $setting->get(),
                'menu' => $setting->getMenu(),
                'category' => $categories
            ]
        );
    }


    private function slugify($title) {
        $slug = str_replace('\'', '', strtolower($title));
        return strip_tags(str_replace(' ', '-', stripslashes($slug))) . '-' . \uniqid();
    }

    /**
     * @Route("/admin/post/new", name="post_new")
     */
    public function post_new(Request $request, SettingService $setting) {
        $post = new Post();

        $form = $this->createForm(PostForm::class, $post, ['cats' => $this->getDoctrine()->getManager()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $data = $form->getData();

            if ($data->getCover()) {
                $file = $post->getCover();
                $filename = md5(uniqid()) . "." . $file->getExtension();

                $data->setCover($filename);

                $file->move(
                    $this->getParameter('cover_folder'),
                    $filename
                );
            }

            $user = $this->getUser();

            if ($form->get('tags')) {
                $tags_str = $form->get('tags')->getData();
                $tags_arr = explode(',', $tags_str);

                for ($i = 0; $i < sizeof($tags_arr); $i++) {
                    $tags_arr[$i] = strtolower(trim($tags_arr[$i]));
                }

                $data->setTags($tags_arr);
            }

            // $data->setCategory($form->get('category'));
            $data->setSlug($this->slugify($form->get('title')->getData()));
            $data->setUser($user);
            $data->setViewCount(0);
            $data->setDateCreated(new \DateTime("now"));

            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();

            return $this->redirectToRoute("post_list");
        }

        return $this->render(
            "blog/post_new.html.twig",
            [
                'form' => $form->createView(),
                'headline' => 'New Post',
                'current' => 'posts',
                'base' => $setting->get()
            ]
        );
    }


    /**
     * @Route("/admin/post/edit/{id}", name="post_edit")
     */
    public function post_edit(Request $request, SettingService $setting, $id) {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->find($id);

        $prevFilename = $post->getCover();

        $tags_str = null;

        if ($post->getTags()) {
            $tags_str = implode(',', $post->getTags());
        }

        if ($post->getCover()) {
            $post->setCover(
                new File($this->getParameter('cover_folder') . '/' . $post->getCover())
            );
        }

        $form = $this->createForm(PostForm::class, $post, ['cats' => $this->getDoctrine()->getManager(), 'tags' => $tags_str]);

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

            
            if ($form->get('tags')) {
                $tags_str = $form->get('tags')->getData();
                $tags_arr = explode(',', $tags_str);

                for ($i = 0; $i < sizeof($tags_arr); $i++) {
                    $tags_arr[$i] = strtolower(trim($tags_arr[$i]));
                }

                $data->setTags($tags_arr);
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
                'current' => 'posts',
                'base' => $setting->get()
            ]
        );

    }


    /**
     * @Route("/post/delete/{id}", name="delete")
     */
    public function post_delete($id) {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->find($id);
        $comments = $em->getRepository(Comment::class)->findBy(['post' => $id]);

        foreach ($comments as $comment) {
            $comment->setPost(null);
            $em->persist($comment);
            $em->flush();
        } 

        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute("post_list");
    }


    /**
     * @Route("/post/{slug}", name="post_single")
     */
    public function post_single(Request $request, SettingService $setting, $slug) {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->findOneBy(['slug' => $slug]);

        $icons = null;

        if ($post->getSharingIcons()) {
            $icons = $em->getRepository(Sharer::class)->findAll();
        }

        $comments = $post->getComments();

        $comment_form = $this->createForm(CommentForm::class, []);

        $comment_form->handleRequest($request);

        if ($comment_form->isSubmitted() && $comment_form->isValid()) {
            $data = $comment_form->getData();

            $c = new Comment();

            $c->setUser($this->getUser());
            $c->setComment($data['comment']);
            $c->setDateCreated(new \DateTime("now"));
            $c->setPost($post);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($c);
            $manager->flush();

            return $this->redirectToRoute("post_single", ['slug' => $post->getSlug()]);
        }

        // Update view count
        $post->setViewCount($post->getViewCount() + 1);
        $em->flush();

        // Get other posts
        $qb = $em->getRepository(Post::class)
            ->createQueryBuilder('p')
            ->andWhere('p.id != :id')
            ->setParameter('id', $post->getId())
            ->orderBy('p.view_count', 'DESC')
            ->getQuery();

        $all_posts = $qb->setMaxResults(5)->execute();

        return $this->render(
            "blog/post_single.html.twig",
            [
                'post' => $post,
                'posts' => $all_posts,
                'comments' => $comments,
                'comment_form' => $comment_form->createView(),
                'icons' => $icons,
                'base' => $setting->get()
            ]
        );
        
    }
}
