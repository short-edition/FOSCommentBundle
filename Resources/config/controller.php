<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use FOS\CommentBundle\Controller\ThreadController;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('fos_comment.controller.thread', ThreadController::class)
        ->tag('controller.service_arguments')
        ->call('setContainer', [service('service_container')]);
};