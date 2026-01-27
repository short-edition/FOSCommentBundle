<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use FOS\CommentBundle\Twig\CommentExtension;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('fos_comment.twig.comment_extension', CommentExtension::class)
            ->args([
                    service('fos_comment.acl.comment')->nullOnInvalid(),
                    service('fos_comment.acl.vote')->nullOnInvalid(),
                    service('fos_comment.acl.thread')->nullOnInvalid(),
            ])
            ->tag('twig.extension');
};