<?php

/*
 * This file is part of the FOSCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace FOS\CommentBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use FOS\CommentBundle\Model\VotableCommentInterface;
use FOS\CommentBundle\Model\VoteInterface;
use FOS\CommentBundle\Model\VoteManager as BaseVoteManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Default ORM VoteManager.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class VoteManager extends BaseVoteManager
{
    protected EntityManager $em;

    protected EntityRepository $repository;

    protected string $class;

    public function __construct(EventDispatcherInterface $dispatcher, EntityManager $em, string $class)
    {
        parent::__construct($dispatcher);

        $this->em = $em;
        $this->repository = $em->getRepository($class);

        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;
    }

    /**
     * Finds a vote by specified criteria.
     */
    public function findVoteBy(array $criteria): ?VoteInterface
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Finds all votes belonging to a comment.
     */
    public function findVotesByComment(VotableCommentInterface $comment): array
    {
        $qb = $this->repository->createQueryBuilder('v');
        $qb->join('v.comment', 'c');
        $qb->andWhere('c.id = :commentId');
        $qb->setParameter('commentId', $comment->id);

        return $qb->getQuery()->execute();
    }

    /**
     * Returns the fully qualified comment vote class name.
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Persists a vote.
     */
    protected function doSaveVote(VoteInterface $vote): void
    {
        $this->em->persist($vote->getComment());
        $this->em->persist($vote);
        $this->em->flush();
    }
}
