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
use FOS\CommentBundle\Model\ThreadInterface;
use FOS\CommentBundle\Model\ThreadManager as BaseThreadManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Default ORM ThreadManager.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class ThreadManager extends BaseThreadManager
{
    protected EntityManager $em;

    protected EntityRepository $repository;

    /**
     * @var string
     */
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
     * Finds one comment thread by the given criteria.
     */
    public function findThreadBy(array $criteria): ?ThreadInterface
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
        return !$this->em->getUnitOfWork()->isInIdentityMap($thread);
    }

    /**
     * Returns the fully qualified comment thread class name.
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Saves a thread.
     */
    protected function doSaveThread(ThreadInterface $thread): void
    {
        $this->em->persist($thread);
        $this->em->flush();
    }
}
