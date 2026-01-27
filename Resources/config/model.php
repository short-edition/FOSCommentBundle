<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->alias(
        FOS\CommentBundle\Model\CommentManagerInterface::class,
        'fos_comment.manager.comment'
    );

    $services->alias(
        FOS\CommentBundle\Model\ThreadManagerInterface::class,
        'fos_comment.manager.thread'
    );

    $services->alias(
        FOS\CommentBundle\Model\VoteManagerInterface::class,
        'fos_comment.manager.vote'
    );
};