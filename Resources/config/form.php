<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use FOS\CommentBundle\Form\CommentableThreadType;
use FOS\CommentBundle\Form\CommentType;
use FOS\CommentBundle\Form\DeleteCommentType;
use FOS\CommentBundle\Form\ThreadType;
use FOS\CommentBundle\Form\VoteType;
use FOS\CommentBundle\FormFactory\CommentableThreadFormFactory;
use FOS\CommentBundle\FormFactory\CommentFormFactory;
use FOS\CommentBundle\FormFactory\DeleteCommentFormFactory;
use FOS\CommentBundle\FormFactory\ThreadFormFactory;
use FOS\CommentBundle\FormFactory\VoteFormFactory;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('fos_comment.form_type.comment.default', CommentType::class)
        ->args([param('fos_comment.model.comment.class')])
        ->tag('form.type', ['alias' => 'fos_comment_comment']);

    $services->set('fos_comment.form_factory.comment.default', CommentFormFactory::class)
        ->args([
            service('form.factory'),
            param('fos_comment.form.comment.type'),
            param('fos_comment.form.comment.name'),
        ]);

    $services->set('fos_comment.form_type.commentable_thread.default', CommentableThreadType::class)
        ->args([param('fos_comment.model.thread.class')])
        ->tag('form.type', [
            'alias' => 'fos_comment_commentable_thread',
            'extended-type' => CommentableThreadType::class,
        ]);

    $services->set('fos_comment.form_factory.commentable_thread.default', CommentableThreadFormFactory::class)
        ->args([
            service('form.factory'),
            param('fos_comment.form.commentable_thread.type'),
            param('fos_comment.form.commentable_thread.name'),
        ]);

    $services->set('fos_comment.form_type.delete_comment.default', DeleteCommentType::class)
        ->args([param('fos_comment.model.comment.class')])
        ->tag('form.type', [
            'alias' => 'fos_comment_delete_comment',
            'extended-type' => DeleteCommentType::class,
        ]);

    $services->set('fos_comment.form_factory.delete_comment.default', DeleteCommentFormFactory::class)
        ->args([
            service('form.factory'),
            param('fos_comment.form.delete_comment.type'),
            param('fos_comment.form.delete_comment.name'),
        ]);

    $services->set('fos_comment.form_type.thread.default', ThreadType::class)
        ->args([param('fos_comment.model.thread.class')])
        ->tag('form.type', [
            'alias' => 'fos_comment_thread',
            'extended-type' => ThreadType::class,
        ]);

    $services->set('fos_comment.form_factory.thread.default', ThreadFormFactory::class)
        ->args([
            service('form.factory'),
            param('fos_comment.form.thread.type'),
            param('fos_comment.form.thread.name'),
        ]);

    $services->set('fos_comment.form_type.vote.default', VoteType::class)
        ->args([param('fos_comment.model.vote.class')])
        ->tag('form.type', [
            'alias' => 'fos_comment_vote',
            'extended-type' => VoteType::class,
        ]);

    $services->set('fos_comment.form_factory.vote.default', VoteFormFactory::class)
        ->args([
            service('form.factory'),
            param('fos_comment.form.vote.type'),
            param('fos_comment.form.vote.name'),
        ]);
};