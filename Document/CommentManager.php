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
use FOS\CommentBundle\Model\CommentInterface;
use FOS\CommentBundle\Model\CommentManager as BaseCommentManager;
use FOS\CommentBundle\Model\ThreadInterface;
use FOS\CommentBundle\Sorting\SortingFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Default ODM CommentManager.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class CommentManager extends BaseCommentManager
{
    /**
     * @var DocumentManager
     */
    protected DocumentManager $dm;

    /**
     * @var DocumentRepository
     */
    protected DocumentRepository $repository;

    /**
     * @var string
     */
    protected string $class;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param SortingFactory           $factory
     * @param DocumentManager          $dm
     * @param string                   $class
     */
    public function __construct(EventDispatcherInterface $dispatcher, SortingFactory $factory, DocumentManager $dm, string $class)
    {
        parent::__construct($dispatcher, $factory);

        $this->dm = $dm;
        $this->repository = $dm->getRepository($class);

        $metadata = $dm->getClassMetadata($class);
        $this->class = $metadata->name;
    }

    /**
     * {@inheritdoc}
     */
    public function findCommentsByThread(ThreadInterface $thread, ?int $depth = null, ?string $sorterAlias = null): array
    {
        $qb = $this->repository
            ->createQueryBuilder()
            ->field('thread.$id')->equals($thread->getId())
            ->sort('ancestors', 'ASC');

        if ($depth > 0) {
            // Queries for an additional level so templates can determine
            // if the final 'depth' layer has children.

            $qb->field('depth')->lte($depth + 1);
        }

        $comments = $qb
            ->getQuery()
            ->execute();

        if (null !== $sorterAlias) {
            $sorter = $this->sortingFactory->getSorter($sorterAlias);
            $comments = $sorter->sortFlat($comments);
        }

        return $comments;
    }

    /**
     * {@inheritdoc}
     */
    public function findCommentTreeByCommentId($commentId, ?string $sorterAlias = null): array
    {
        $qb = $this->repository
            ->createQueryBuilder()
            ->field('ancestors')->equals($commentId)
            ->sort('ancestors', 'ASC');

        $comments = $qb->getQuery()->execute();

        if (!$comments) {
            return [];
        }

        $sorter = $this->sortingFactory->getSorter($sorterAlias);

        $singleComment = current($comments->toArray());
        $ignoreParents = $singleComment->getAncestors();

        return $this->organiseComments($comments, $sorter, $ignoreParents);
    }

    /**
     * {@inheritdoc}
     */
    public function findCommentById($id): ?CommentInterface
    {
        return $this->repository->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function isNewComment(CommentInterface $comment): bool
    {
        return !$this->dm->getUnitOfWork()->isInIdentityMap($comment);
    }

    /**
     * {@inheritdoc}
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    protected function doSaveComment(CommentInterface $comment): void
    {
        $this->dm->persist($comment->getThread());
        $this->dm->persist($comment);
        $this->dm->flush();
    }
}
