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
 * Interface to be implemented by comment managers.
 *
 * This adds another level of abstraction between your application, and the actual repository.
 *
 * All changes to comments should happen through this interface.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Tim Nagel <tim@nagel.com.au>
 */
interface CommentManagerInterface
{
    /**
     * Returns a flat array of comments from the specified thread.
     *
     * The sorter parameter should be left alone if you are sorting in the
     * tree methods.
     *
     * @return CommentInterface[] An array of commentInterfaces
     */
    public function findCommentsByThread(ThreadInterface $thread, ?int $depth = null, ?string $sorterAlias = null): array;

    /**
     * Returns all thread comments in a nested array.
     *
     * Will typically be used when it comes to display the comments.
     *
     * Will query for an additional level of depth when provided
     * so templates can determine to display a 'load more comments' link.
     *
     * @return array(
     *                0 => array(
     *                'comment' => CommentInterface,
     *                'children' => array(
     *                0 => array (
     *                'comment' => CommentInterface,
     *                'children' => array(...)
     *                ),
     *                1 => array (
     *                'comment' => CommentInterface,
     *                'children' => array(...)
     *                )
     *                )
     *                ),
     *                1 => array(
     *                ...
     *                )
     */
    public function findCommentTreeByThread(ThreadInterface $thread, ?string $sorterAlias = null, ?int $depth = null): array;

    /**
     * Returns a partial comment tree based on a specific parent commentId.
     *
     * @return array See findCommentTreeByThread()
     */
    public function findCommentTreeByCommentId(mixed $commentId, ?string $sorterAlias = null): array;

    /**
     * Saves a comment to the persistence backend used.
     */
    public function saveComment(CommentInterface $comment);

    /**
     * Finds a comment by it's unique id.
     */
    public function findCommentById(mixed $id): ?CommentInterface;

    /**
     * Creates a new comment object.
     */
    public function createComment(ThreadInterface $thread, ?CommentInterface $parent = null): CommentInterface;

    /**
     * Checks if the comment was already persisted before, or if it's a new one.
     */
    public function isNewComment(CommentInterface $comment): bool;

    /**
     * Returns the fully qualified comment class name.
     */
    public function getClass(): string;
}
