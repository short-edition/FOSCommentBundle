<?php

/*
 * This file is part of the FOSCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace FOS\CommentBundle\EventListener;

use FOS\CommentBundle\Event\VoteEvent;
use FOS\CommentBundle\Events;
use FOS\CommentBundle\Model\SignedVoteInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Assigns a FOS\UserBundle user from the logged in user to a vote.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class VoteBlamerListener implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    protected ?LoggerInterface $logger;
    /**
     * @var AuthorizationCheckerInterface
     */
    private AuthorizationCheckerInterface $authorizationChecker;

    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;

    /**
     * Constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface $tokenStorage
     * @param LoggerInterface|null $logger
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage, ?LoggerInterface $logger = null)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->logger = $logger;
    }

    /**
     * Assigns the Security token's user to the vote.
     *
     * @param VoteEvent $event
     */
    public function blame(VoteEvent $event): void
    {
        $vote = $event->getVote();

        if (!$vote instanceof SignedVoteInterface) {
            $this->logger?->debug('Vote does not implement SignedVoteInterface, skipping');

            return;
        }

        if (null === $this->tokenStorage->getToken()) {
            $this->logger?->debug('There is no firewall configured. We cant get a user.');

            return;
        }

        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $vote->setVoter($this->tokenStorage->getToken()->getUser());
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [Events::VOTE_PRE_PERSIST => 'blame'];
    }
}
