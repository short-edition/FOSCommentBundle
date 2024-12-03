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

use FOS\CommentBundle\Model\VotableCommentInterface;
use FOS\CommentBundle\Model\VoteInterface;
use FOS\CommentBundle\Model\VoteManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Wraps a real implementation of VoteManagerInterface and
 * performs Acl checks with the configured Vote Acl service.
 *
 * @author Tim Nagel <tim@nagel.com.au
 */
class AclVoteManager implements VoteManagerInterface
{
    /**
     * The VoteManager instance to be wrapped with ACL.
     *
     * @var VoteManagerInterface
     */
    protected VoteManagerInterface $realManager;

    /**
     * The Vote Acl instance for querying Acls.
     *
     * @var VoteAclInterface
     */
    protected VoteAclInterface $voteAcl;

    /**
     * The Comment Acl for querying Acls.
     *
     * @var CommentAclInterface
     */
    protected CommentAclInterface $commentAcl;

    /**
     * {@inheritdoc}
     */
    public function __construct(VoteManagerInterface $voteManager, VoteAclInterface $voteAcl, CommentAclInterface $commentAcl)
    {
        $this->realManager = $voteManager;
        $this->voteAcl = $voteAcl;
        $this->commentAcl = $commentAcl;
    }

    /**
     * {@inheritdoc}
     */
    public function findVoteById(string $id): ?VoteInterface
    {
        $vote = $this->realManager->findVoteById($id);

        if ($vote && !$this->voteAcl->canView($vote)) {
            throw new AccessDeniedException();
        }

        return $vote;
    }

    /**
     * {@inheritdoc}
     */
    public function findVoteBy(array $criteria): ?VoteInterface
    {
        $vote = $this->realManager->findVoteBy($criteria);

        if ($vote && !$this->voteAcl->canView($vote)) {
            throw new AccessDeniedException();
        }

        return $vote;
    }

    /**
     * {@inheritdoc}
     */
    public function findVotesByComment(VotableCommentInterface $comment): array
    {
        $votes = $this->realManager->findVotesByComment($comment);

        foreach ($votes as $vote) {
            if (!$this->voteAcl->canView($vote)) {
                throw new AccessDeniedException();
            }
        }

        return $votes;
    }

    /**
     * {@inheritdoc}
     */
    public function saveVote(VoteInterface $vote): void
    {
        if (!$this->voteAcl->canCreate()) {
            throw new AccessDeniedException();
        }

        if (!$this->commentAcl->canView($vote->getComment())) {
            throw new AccessDeniedException();
        }

        $this->realManager->saveVote($vote);
        $this->voteAcl->setDefaultAcl($vote);
    }

    /**
     * {@inheritdoc}
     */
    public function createVote(VotableCommentInterface $comment): VoteInterface
    {
        return $this->realManager->createVote($comment);
    }

    /**
     * {@inheritdoc}
     */
    public function getClass(): string
    {
        return $this->realManager->getClass();
    }
}
