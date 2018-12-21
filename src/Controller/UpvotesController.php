<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

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
        $comment_ = $em->getRepository(\App\Entity\Comment::class)->find($comment);
        
        $upvote = null;

        if ($uvid > 0){
            $upvote = $em->getRepository(Upvote::class)->createQueryBuilder('u')
                ->andWhere('u.id = :uvid')
                ->andWhere('u.comment = :cid')
                ->andWhere('u.user = :uid')
                ->setParameter('uvid', $uvid)
                ->setParameter('uid', $this->getUser())
                ->setParameter('cid', $comment_)
                ->setMaxResults(1)
                ->getQuery()
                ->getResult();

                $upvote = $upvote[0];
            }


        $wasNull = false;

        if ($upvote == null) {
            $upvote = new Upvote();
            $wasNull = true;
        }

        /**
         * @var App\Entity\CommentVotes;
         */

        $newType = $type;

        if (!$wasNull) {
            if ($type == 1 && $upvote->getType() == 1) {
                $newType = 0;
            } else if ($type == -1 && $upvote->getType() == -1) {
                $newType = 0;
            }
        }

        $upvote->setType($newType);
        $upvote->setUser($this->getUser());
        $upvote->setComment($comment_);
        $upvote->setPost($em->getRepository(\App\Entity\Post::class)->findOneBy(['slug' => $pid]));

        if ($uvid == "0") {
            $em->persist($upvote);
        }

        $em->flush();

        $counter = $em->getRepository(Upvote::class)->getVoteCount($upvote->getPost(), $upvote->getComment());
        $counter[0]['type'] = $newType;

        return new \Symfony\Component\HttpFoundation\JsonResponse(
            $counter[0]
        );
    }
}
