<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use FOS\CommentBundle\Acl\AclCommentManager;
use FOS\CommentBundle\Acl\AclThreadManager;
use FOS\CommentBundle\Acl\AclVoteManager;
use FOS\CommentBundle\Acl\RoleCommentAcl;
use FOS\CommentBundle\Acl\RoleThreadAcl;
use FOS\CommentBundle\Acl\RoleVoteAcl;
use FOS\CommentBundle\Acl\SecurityCommentAcl;
use FOS\CommentBundle\Acl\SecurityThreadAcl;
use FOS\CommentBundle\Acl\SecurityVoteAcl;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('fos_comment.acl.thread.security', SecurityThreadAcl::class)
        ->args([
            service('security.authorization_checker'),
            service('security.acl.object_identity_retrieval_strategy'),
            service('security.acl.provider'),
            param('fos_comment.model.thread.class'),
        ]);

    $services->set('fos_comment.acl.comment.security', SecurityCommentAcl::class)
        ->args([
            service('security.authorization_checker'),
            service('security.acl.object_identity_retrieval_strategy'),
            service('security.acl.provider'),
            param('fos_comment.model.comment.class'),
        ]);

    $services->set('fos_comment.acl.vote.security', SecurityVoteAcl::class)
        ->args([
            service('security.authorization_checker'),
            service('security.acl.object_identity_retrieval_strategy'),
            service('security.acl.provider'),
            param('fos_comment.model.vote.class'),
        ]);

    $services->set('fos_comment.acl.thread.roles', RoleThreadAcl::class)
        ->args([
            service('security.authorization_checker'),
            null, // Create role
            null, // View role
            null, // Edit role
            null, // Delete role
            param('fos_comment.model.thread.class'),
        ]);

    $services->set('fos_comment.acl.comment.roles', RoleCommentAcl::class)
        ->args([
            service('security.authorization_checker'),
            null, // Create role
            null, // View role
            null, // Edit role
            null, // Delete role
            param('fos_comment.model.comment.class'),
        ]);

    $services->set('fos_comment.acl.vote.roles', RoleVoteAcl::class)
        ->args([
            service('security.authorization_checker'),
            null, // Create role
            null, // View role
            null, // Edit role
            null, // Delete role
            param('fos_comment.model.vote.class'),
        ]);

    $services->set('fos_comment.manager.thread.acl', AclThreadManager::class)
        ->args([
            service('fos_comment.manager.thread.default'),
            service('fos_comment.acl.thread'),
        ]);

    $services->set('fos_comment.manager.comment.acl', AclCommentManager::class)
        ->args([
            service('fos_comment.manager.comment.default'),
            service('fos_comment.acl.comment'),
            service('fos_comment.acl.thread'),
        ]);

    $services->set('fos_comment.manager.vote.acl', AclVoteManager::class)
        ->args([
            service('fos_comment.manager.vote.default'),
            service('fos_comment.acl.vote'),
            service('fos_comment.acl.comment'),
        ]);

    // Alias "courts" attendus par les managers (fos_comment.acl.thread/comment/vote)
    $services->alias('fos_comment.acl.thread', 'fos_comment.acl.thread.security');
    $services->alias('fos_comment.acl.comment', 'fos_comment.acl.comment.security');
    $services->alias('fos_comment.acl.vote', 'fos_comment.acl.vote.security');
};