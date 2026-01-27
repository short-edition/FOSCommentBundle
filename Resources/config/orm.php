<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use FOS\CommentBundle\Entity\Comment;
use FOS\CommentBundle\Entity\CommentManager;
use FOS\CommentBundle\Entity\Thread;
use FOS\CommentBundle\Entity\ThreadManager;
use FOS\CommentBundle\Entity\Vote;
use FOS\CommentBundle\Entity\VoteManager;

return static function (ContainerConfigurator $container): void {
    $parameters = $container->parameters();

    $parameters->set('fos_comment.model.thread.class', Thread::class);
    $parameters->set('fos_comment.model.comment.class', Comment::class);
    $parameters->set('fos_comment.model.vote.class', Vote::class);

    $services = $container->services();

    $services->set('fos_comment.manager.thread.default', ThreadManager::class)
            ->args([
                    service('event_dispatcher'),
                    service('fos_comment.entity_manager'),
                    param('fos_comment.model.thread.class'),
            ]);

    $services->set('fos_comment.manager.comment.default', CommentManager::class)
            ->args([
                    service('event_dispatcher'),
                    service('fos_comment.sorting_factory'),
                    service('fos_comment.entity_manager'),
                    param('fos_comment.model.comment.class'),
            ]);

    $services->set('fos_comment.manager.vote.default', VoteManager::class)
            ->args([
                    service('event_dispatcher'),
                    service('fos_comment.entity_manager'),
                    param('fos_comment.model.vote.class'),
            ]);
};