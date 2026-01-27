<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use FOS\CommentBundle\Sorting\DateSorting;
use FOS\CommentBundle\Sorting\SortingFactory;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('fos_comment.sorter.date_asc', DateSorting::class)
            ->tag('fos_comment.sorter', ['alias' => 'date_asc'])
            ->args(['ASC']);

    $services->set('fos_comment.sorter.date_desc', DateSorting::class)
            ->tag('fos_comment.sorter', ['alias' => 'date_desc'])
            ->args(['DESC']);

    $services->set('fos_comment.sorting_factory', SortingFactory::class)
            ->args([
                    [], // sera remplacé par ton CompilerPass (tag fos_comment.sorter)
                    param('fos_comment.sorting_factory.default_sorter'),
            ]);
};