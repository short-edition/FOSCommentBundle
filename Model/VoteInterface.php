<?php

/*
 * This file is part of the FOSCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace FOS\CommentBundle\Model;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\ExecutionContextInterface as LegacyExecutionContextInterface;

/**
 * Methods a vote should implement.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
interface VoteInterface
{
    const int VOTE_UP = 1;
    const int VOTE_DOWN = -1;

    /**
     * @return VotableCommentInterface
     */
    public function getComment(): ?VotableCommentInterface;

    /**
     * @param VotableCommentInterface $comment
     */
    public function setComment(VotableCommentInterface $comment): void;

    /**
     * @return int the modification applied to the comment by this vote
     */
    public function getValue(): int;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime;

    /**
     * @param LegacyExecutionContextInterface|ExecutionContextInterface $context
     */
    public function isVoteValid($context);
}
