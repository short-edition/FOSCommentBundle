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

/**
 * Storage agnostic comment thread object.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
abstract class Thread implements ThreadInterface
{
    /**
     * Id, a unique string that binds the comments together in a thread (tree).
     * It can be a url or really anything unique.
     *
     * @var string
     */
    protected ?int $id = null;

    /**
     * Tells if new comments can be added in this thread.
     *
     * @var bool
     */
    protected bool $isCommentable = true;

    /**
     * Denormalized number of comments.
     *
     * @var int
     */
    protected int $numComments = 0;

    /**
     * Denormalized date of the last comment.
     *
     * @var DateTime
     */
    protected ?DateTime $lastCommentAt = null;

    /**
     * Url of the page where the thread lives.
     *
     * @var string
     */
    protected string $permalink;

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Comment thread #'.$this->getId();
    }

    /**
     * @return string
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param  string
     *
     * @return null
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPermalink(): string
    {
        return $this->permalink;
    }

    public function setPermalink(string $permalink): void
    {
        $this->permalink = $permalink;
    }

    /**
     * @return bool
     */
    public function isCommentable(): bool
    {
        return $this->isCommentable;
    }

    public function setCommentable($isCommentable): void
    {
        $this->isCommentable = (bool) $isCommentable;
    }

    /**
     * Gets the number of comments.
     *
     * @return int
     */
    public function getNumComments(): int
    {
        return $this->numComments;
    }

    /**
     * Sets the number of comments.
     *
     * @param int $numComments
     */
    public function setNumComments(int $numComments): void
    {
        $this->numComments = intval($numComments);
    }

    /**
     * Increments the number of comments by the supplied
     * value.
     *
     * @param int $by Value to increment comments by
     *
     * @return int The new comment total
     */
    public function incrementNumComments(int $by = 1): int
    {
        return $this->numComments += intval($by);
    }

    /**
     * @return DateTime
     */
    public function getLastCommentAt(): ?DateTime
    {
        return $this->lastCommentAt;
    }

    /**
     * @param  DateTime
     */
    public function setLastCommentAt($lastCommentAt): void
    {
        $this->lastCommentAt = $lastCommentAt;
    }
}
