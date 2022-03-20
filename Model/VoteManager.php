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

use FOS\CommentBundle\Event\VoteEvent;
use FOS\CommentBundle\Event\VotePersistEvent;
use FOS\CommentBundle\Events;
use InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\LegacyEventDispatcherProxy;

/**
 * Abstract VotingManager.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
abstract class VoteManager implements VoteManagerInterface
{
    protected EventDispatcherInterface $dispatcher;

    /**
     * Constructor.
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Finds a vote by id.
     *
     * @param  $id
     */
    public function findVoteById($id): VoteInterface
    {
        return $this->findVoteBy(['id' => $id]);
    }

    /**
     * Creates a Vote object.
     *
     * @param VotableCommentInterface $comment
     */
    public function createVote(VotableCommentInterface $comment): VoteInterface
    {
        $class = $this->getClass();
        $vote = new $class();
        $vote->setComment($comment);

        $event = new VoteEvent($vote);
        $this->dispatcher->dispatch($event, Events::VOTE_CREATE);

        return $vote;
    }

    /**
     * @param VoteInterface $vote
     */
    public function saveVote(VoteInterface $vote): void
    {
        if (null === $vote->getComment()) {
            throw new InvalidArgumentException('Vote passed into saveVote must have a comment');
        }

        $event = new VotePersistEvent($vote);
        $this->dispatcher->dispatch($event, Events::VOTE_PRE_PERSIST);

        if ($event->isPersistenceAborted()) {
            return;
        }

        $this->doSaveVote($vote);

        $event = new VoteEvent($vote);
        $this->dispatcher->dispatch($event, Events::VOTE_POST_PERSIST);
    }

    /**
     * Performs the persistence of the Vote.
     *
     * @abstract
     *
     * @param VoteInterface $vote
     */
    abstract protected function doSaveVote(VoteInterface $vote);
}
