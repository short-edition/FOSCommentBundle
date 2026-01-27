<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use FOS\CommentBundle\EventListener\CommentSpamListener;
use FOS\CommentBundle\SpamDetection\AkismetSpamDetection;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('fos_comment.spam_detection.comment.akismet', AkismetSpamDetection::class)
            ->args([
                    service('ornicar_akismet'),
            ]);

    $services->set('fos_comment.listener.comment_spam', CommentSpamListener::class)
            ->tag('kernel.event_subscriber')
            ->args([
                    service('fos_comment.spam_detection.comment'),
            ]);
};