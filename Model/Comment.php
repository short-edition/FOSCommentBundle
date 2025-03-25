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

use DateTime;
use InvalidArgumentException;

/**
 * Storage agnostic comment object.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
abstract class Comment implements CommentInterface
{
    /**
     * Parent comment id.
     *
     * @var CommentInterface
     */
    protected ?CommentInterface $parent = null;

    /**
     * Comment text.
     *
     * @var string
     */
    protected string $body;

    /**
     * The depth of the comment.
     *
     * @var int
     */
    protected int $depth = 0;

    /**
     * @var DateTime
     */
    protected DateTime $createdAt;

    /**
     * Current state of the comment.
     *
     * @var int
     */
    protected int $state = 0;

    /**
     * The previous state of the comment.
     *
     * @var int
     */
    protected int $previousState = 0;

    /**
     * Should be mapped by the end developer.
     *
     * @var ThreadInterface
     */
    protected ?ThreadInterface $thread = null;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Comment #'.$this->id;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string
     *
     * @return void
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return string name of the comment author
     */
    public function getAuthorName(): string
    {
        return 'Anonymous';
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * Sets the creation date.
     *
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Returns the depth of the comment.
     *
     * @return int
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?CommentInterface
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(CommentInterface $parent): void
    {
        $this->parent = $parent;

        if (!$parent->id) {
            throw new InvalidArgumentException('Parent comment must be persisted.');
        }

        $ancestors = $parent->getAncestors();
        $ancestors[] = $parent->id;

        $this->setAncestors($ancestors);
    }

    /**
     * @return ThreadInterface
     */
    public function getThread(): ?ThreadInterface
    {
        return $this->thread;
    }

    /**
     * @param ThreadInterface $thread
     *
     * @return void
     */
    public function setThread(ThreadInterface $thread): void
    {
        $this->thread = $thread;
    }

    /**
     * {@inheritdoc}
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * {@inheritdoc}
     */
    public function setState($state): void
    {
        $this->previousState = $this->state;
        $this->state = $state;
    }

    /**
     * {@inheritdoc}
     */
    public function getPreviousState(): int
    {
        return $this->previousState;
    }
}
