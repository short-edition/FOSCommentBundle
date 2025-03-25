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
 * Binds a comment tree to anything, using a unique, arbitrary id.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
interface ThreadInterface
{
    /**
     * @param string
     */
    public function setId(int $id);

    /**
     * Url of the page where the thread lives.
     *
     * @return string
     */
    public function getPermalink(): string;

    /**
     * @param  string
     *
     * @return null
     */
    public function setPermalink(string $permalink): void;

    /**
     * Tells if new comments can be added in this thread.
     *
     * @return bool
     */
    public function isCommentable(): bool;

    /**
     * @param bool $isCommentable
     */
    public function setCommentable(bool $isCommentable): void;

    /**
     * Gets the number of comments.
     *
     * @return int
     */
    public function getNumComments(): int;

    /**
     * Sets the number of comments.
     *
     * @param int $numComments
     */
    public function setNumComments(int $numComments): void;

    /**
     * Increments the number of comments by the supplied
     * value.
     *
     * @param int $by The number of comments to increment by
     *
     * @return int The new comment total
     */
    public function incrementNumComments(int $by): int;

    /**
     * Denormalized date of the last comment.
     *
     * @return DateTime
     */
    public function getLastCommentAt(): ?DateTime;

    /**
     * @param  DateTime
     *
     * @return null
     */
    public function setLastCommentAt(DateTime $lastCommentAt): void;
}
