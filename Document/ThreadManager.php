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
use FOS\CommentBundle\Model\ThreadInterface;
use FOS\CommentBundle\Model\ThreadManager as BaseThreadManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Default ODM ThreadManager.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class ThreadManager extends BaseThreadManager
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
     * @param DocumentManager $dm
     * @param string          $class
     */
    public function __construct(EventDispatcherInterface $dispatcher, DocumentManager $dm, string $class)
    {
        parent::__construct($dispatcher);

        $this->dm = $dm;
        $this->repository = $dm->getRepository($class);

        $metadata = $dm->getClassMetadata($class);
        $this->class = $metadata->name;
    }

    /**
     * Finds one comment thread by the given criteria.
     *
     * @param array $criteria
     *
     * @return ThreadInterface
     */
    public function findThreadBy(array $criteria): ThreadInterface
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function findThreadsBy(array $criteria): array
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Finds all threads.
     *
     * @return array of ThreadInterface
     */
    public function findAllThreads(): array
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function isNewThread(ThreadInterface $thread): bool
    {
        return !$this->dm->getUnitOfWork()->isInIdentityMap($thread);
    }

    /**
     * Returns the fully qualified comment thread class name.
     *
     * @return string
     **/
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Saves a thread.
     *
     * @param ThreadInterface $thread
     */
    protected function doSaveThread(ThreadInterface $thread): void
    {
        $this->dm->persist($thread);
        $this->dm->flush();
    }
}
