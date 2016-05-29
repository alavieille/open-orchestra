<?php

namespace OpenOrchestra\WorkflowFunctionModelBundle;

use OpenOrchestra\WorkflowFunctionModelBundle\DependencyInjection\Compiler\EntityResolverCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class OpenOrchestraWorkflowFunctionModelBundle
 */
class OpenOrchestraWorkflowFunctionModelBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new EntityResolverCompilerPass());
    }
}
