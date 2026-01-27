<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use FOS\CommentBundle\EventListener\CommentMarkupListener;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('fos_comment.listener.comment_markup', CommentMarkupListener::class)
            ->tag('kernel.event_subscriber')
            ->arg(0, service('fos_comment.markup'));
};