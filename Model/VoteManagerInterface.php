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

/**
 * Manages voting scores for comments.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
interface VoteManagerInterface
{
    /**
     * Returns the class of the Vote object.
     */
    public function getClass(): string;

    /**
     * Creates a Vote object.
     */
    public function createVote(VotableCommentInterface $comment): VoteInterface;

    /**
     * Persists a vote.
     */
    public function saveVote(VoteInterface $vote): void;

    /**
     * Finds a vote by specified criteria.
     */
    public function findVoteBy(array $criteria): ?VoteInterface;

    /**
     * Finds a vote by id.
     */
    public function findVoteById(string $id): ?VoteInterface;

    /**
     * Finds all votes for a comment.
     */
    public function findVotesByComment(VotableCommentInterface $comment): array;
}
