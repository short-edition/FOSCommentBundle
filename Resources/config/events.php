<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use FOS\CommentBundle\EventListener\ClosedThreadListener;
use FOS\CommentBundle\EventListener\CommentBlamerListener;
use FOS\CommentBundle\EventListener\CommentVoteScoreListener;
use FOS\CommentBundle\EventListener\ThreadCountersListener;
use FOS\CommentBundle\EventListener\ThreadPermalinkListener;
use FOS\CommentBundle\EventListener\VoteBlamerListener;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('fos_comment.listener.comment_vote_score', CommentVoteScoreListener::class)
        ->tag('kernel.event_subscriber');

    $services->set('fos_comment.listener.thread_counters', ThreadCountersListener::class)
        ->args([
            service('fos_comment.manager.comment'),
        ])
        ->tag('kernel.event_subscriber');

    $services->set('fos_comment.listener.thread_permalink', ThreadPermalinkListener::class)
        ->args([
            service('request_stack'),
        ])
        ->tag('kernel.event_subscriber');

    $services->set('fos_comment.listener.comment_blamer', CommentBlamerListener::class)
        ->args([
            service('security.authorization_checker'),
            service('security.token_storage'),
            service('logger')->nullOnInvalid(),
        ])
        ->tag('kernel.event_subscriber');

    $services->set('fos_comment.listener.vote_blamer', VoteBlamerListener::class)
        ->args([
            service('security.authorization_checker'),
            service('security.token_storage'),
            service('logger')->nullOnInvalid(),
        ])
        ->tag('kernel.event_subscriber');

    $services->set('fos_comment.listener.closed_threads', ClosedThreadListener::class)
        ->tag('kernel.event_subscriber');
};