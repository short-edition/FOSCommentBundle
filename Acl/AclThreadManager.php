<?php

/*
 * This file is part of the FOSCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace FOS\CommentBundle\Acl;

use FOS\CommentBundle\Model\ThreadInterface;
use FOS\CommentBundle\Model\ThreadManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Wraps a real implementation of ThreadManagerInterface and
 * performs Acl checks with the configured Thread Acl service.
 *
 * @author Tim Nagel <tim@nagel.com.au
 */
class AclThreadManager implements ThreadManagerInterface
{
    /**
     * The ThreadManager instance to be wrapped with ACL.
     *
     * @var ThreadManagerInterface
     */
    protected ThreadManagerInterface $realManager;

    /**
     * The Thread Acl instance for querying Acls.
     *
     * @var ThreadAclInterface
     */
    protected ThreadAclInterface $threadAcl;

    /**
     * Constructor.
     *
     * @param ThreadManagerInterface $threadManager The concrete ThreadManager service
     * @param ThreadAclInterface     $threadAcl     The Thread Acl service
     */
    public function __construct(ThreadManagerInterface $threadManager, ThreadAclInterface $threadAcl)
    {
        $this->realManager = $threadManager;
        $this->threadAcl = $threadAcl;
    }

    /**
     * {@inheritdoc}
     */
    public function findThreadById(string $id): ThreadInterface
    {
        $thread = $this->realManager->findThreadById($id);

        if (null !== $thread && !$this->threadAcl->canView($thread)) {
            throw new AccessDeniedException();
        }

        return $thread;
    }

    /**
     * {@inheritdoc}
     */
    public function findThreadBy(array $criteria): ThreadInterface
    {
        $thread = $this->realManager->findThreadBy($criteria);

        if (null !== $thread && !$this->threadAcl->canView($thread)) {
            throw new AccessDeniedException();
        }

        return $thread;
    }

    /**
     * {@inheritdoc}
     */
    public function findThreadsBy(array $criteria): array
    {
        $threads = $this->realManager->findThreadsBy($criteria);

        foreach ($threads as $thread) {
            if (!$this->threadAcl->canView($thread)) {
                throw new AccessDeniedException();
            }
        }

        return $threads;
    }

    /**
     * {@inheritdoc}
     */
    public function findAllThreads(): array
    {
        $threads = $this->realManager->findAllThreads();

        foreach ($threads as $thread) {
            if (!$this->threadAcl->canView($thread)) {
                throw new AccessDeniedException();
            }
        }

        return $threads;
    }

    /**
     * {@inheritdoc}
     */
    public function createThread(?string $id = null): \FOS\CommentBundle\Model\Thread
    {
        return $this->realManager->createThread($id);
    }

    /**
     * {@inheritdoc}
     */
    public function saveThread(ThreadInterface $thread): void
    {
        if (!$this->threadAcl->canCreate()) {
            throw new AccessDeniedException();
        }

        $newThread = $this->isNewThread($thread);

        if (!$newThread && !$this->threadAcl->canEdit($thread)) {
            throw new AccessDeniedException();
        }

        $this->realManager->saveThread($thread);

        if ($newThread) {
            $this->threadAcl->setDefaultAcl($thread);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isNewThread(ThreadInterface $thread): bool
    {
        return $this->realManager->isNewThread($thread);
    }

    /**
     * {@inheritdoc}
     */
    public function getClass(): string
    {
        return $this->realManager->getClass();
    }
}
