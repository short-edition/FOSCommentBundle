<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use FOS\CommentBundle\Model\Comment;
use FOS\CommentBundle\Model\CommentManager;
use FOS\CommentBundle\Model\Thread;
use FOS\CommentBundle\Model\ThreadManager;
use FOS\CommentBundle\Model\Vote;
use FOS\CommentBundle\Model\VoteManager;

return static function (ContainerConfigurator $container): void {
    $parameters = $container->parameters();
    $services = $container->services();

    // Paramètres
    $parameters->set('fos_comment.model.thread.class', Thread::class);
    $parameters->set('fos_comment.model.comment.class', Comment::class);
    $parameters->set('fos_comment.model.vote.class', Vote::class);

    // Services
    $services->set('fos_comment.manager.thread.default', ThreadManager::class)
        ->args([
            service('event_dispatcher'),
            service('fos_comment.document_manager'),
            param('fos_comment.model.thread.class'),
        ]);

    $services->set('fos_comment.manager.comment.default', CommentManager::class)
        ->args([
            service('event_dispatcher'),
            service('fos_comment.sorting_factory'),
            service('fos_comment.document_manager'),
            param('fos_comment.model.comment.class'),
        ]);

    $services->set('fos_comment.manager.vote.default', VoteManager::class)
        ->args([
            service('event_dispatcher'),
            service('fos_comment.document_manager'),
            param('fos_comment.model.vote.class'),
        ]);
};