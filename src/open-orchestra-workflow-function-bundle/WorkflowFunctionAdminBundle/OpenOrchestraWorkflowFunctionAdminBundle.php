<?php

namespace OpenOrchestra\WorkflowFunctionAdminBundle;

use OpenOrchestra\WorkflowFunctionAdminBundle\DependencyInjection\Compiler\RoleCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use OpenOrchestra\WorkflowFunctionAdminBundle\DependencyInjection\Compiler\RoleUsageCompilerPass;
use OpenOrchestra\WorkflowFunctionAdminBundle\DependencyInjection\Compiler\TwigGlobalsCompilerPass;

/**
 * Class OpenOrchestraWorkflowFunctionAdminBundle
 */
class OpenOrchestraWorkflowFunctionAdminBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TwigGlobalsCompilerPass());
        $container->addCompilerPass(new RoleCompilerPass());
        $container->addCompilerPass(new RoleUsageCompilerPass());
    }
}
