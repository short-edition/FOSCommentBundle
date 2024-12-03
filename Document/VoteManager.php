<?php

/*
 * This file is part of the FOSCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace FOS\CommentBundle\Document;

use Doctrine\ODM\MongoDB\DocumentManager;
use FOS\CommentBundle\Model\VotableCommentInterface;
use FOS\CommentBundle\Model\VoteInterface;
use FOS\CommentBundle\Model\VoteManager as BaseVoteManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Default ODM VoteManager.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class VoteManager extends BaseVoteManager
{
    protected DocumentManager $dm;

    protected DocumentRepository $repository;

    /**
     * @var string
     */
    protected string $class;

    public function __construct(EventDispatcherInterface $dispatcher, DocumentManager $dm, string $class)
    {
        parent::__construct($dispatcher);

        $this->dm = $dm;
        $this->repository = $dm->getRepository($class);

        $metadata = $dm->getClassMetadata($class);
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
        $qb = $this->repository->createQueryBuilder();
        $qb->field('comment.$id')->equals($comment->getId());
        $qb->sort('createdAt', 'ASC');

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
        $this->dm->persist($vote->getComment());
        $this->dm->persist($vote);
        $this->dm->flush();
    }
}
