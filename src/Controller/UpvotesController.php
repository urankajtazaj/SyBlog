<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\CommentVotes as Upvote;

class UpvotesController extends AbstractController
{
    /**
     * @Route("/upvotes/{comment}/{type}/{uvid}/{pid}", name="vote")
     */
    public function index($comment, $type, $uvid = 0, $pid)
    {

        /**
         *  type 
         *      case  1: upvoted
         *      case  0: none
         *      case -1: downvoted
         */

        $em = $this->getDoctrine()->getManager();
        
        $upvote = null;

        if ($uvid > 0)
            $upvote = $em->getRepository(Upvote::class)->find($uvid);
        
        $wasNull = false;

        if ($upvote == null) {
            $upvote = new Upvote();
            $wasNull = true;
        }

        /**
         * @var App\Entity\CommentVotes;
         */
        
        $upvote->setType($type);
        $upvote->setUser($this->getUser());
        $upvote->setComment($em->getRepository(\App\Entity\Comment::class)->find($comment));
        $upvote->setPost($em->getRepository(\App\Entity\Post::class)->findOneBy(['slug' => $pid]));

        if ($uvid == "0") {
            $em->persist($upvote);
        }
        // dd($upvote);

        $em->flush();

        return $this->redirectToRoute('post_single', [
            'slug' => $pid
        ]);
    }
}
