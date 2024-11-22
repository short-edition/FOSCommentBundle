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
 * CommentInterface.
 *
 * Any comment to be used by FOS\CommentBundle must implement this interface.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
interface CommentInterface
{
    const int STATE_VISIBLE = 0;

    const int STATE_DELETED = 1;

    const int STATE_SPAM = 2;

    const int STATE_PENDING = 3;

    /**
     * @return mixed unique ID for this comment
     */
    public function getId(): mixed;

    /**
     * @return string name of the comment author
     */
    public function getAuthorName(): string;

    /**
     * @return string
     */
    public function getBody(): string;

    /**
     * @param string $body
     */
    public function setBody(string $body);

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime;

    /**
     * @return ThreadInterface
     */
    public function getThread();

    /**
     * @param ThreadInterface $thread
     */
    public function setThread(Thread $thread);

    /**
     * @return CommentInterface
     */
    public function getParent();

    /**
     * @param CommentInterface $comment
     */
    public function setParent(self $comment);

    /**
     * @return int The current state of the comment
     */
    public function getState(): int;

    /**
     * @param int $state
     */
    public function setState(int $state);

    /**
     * Gets the previous state.
     *
     * @return int
     */
    public function getPreviousState(): int;
}
