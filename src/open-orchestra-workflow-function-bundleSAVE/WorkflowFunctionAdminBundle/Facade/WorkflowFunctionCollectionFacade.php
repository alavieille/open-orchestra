<?php

namespace OpenOrchestra\WorkflowFunctionAdminBundle\Facade;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\AbstractFacade;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class WorkflowFunctionCollectionFacade
 */
class WorkflowFunctionCollectionFacade extends AbstractFacade
{
    /**
     * @Serializer\Type("string")
     */
    public $collectionName = 'workflow_functions';

    /**
     * @Serializer\Type("array<OpenOrchestra\WorkflowFunctionAdminBundle\Facade\WorkflowFunctionFacade>")
     */
    public $workflowFunctions = array();

    /**
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("recordsTotal")
     */
    public $recordsTotal;

    /**
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("recordsFiltered")
     */
    public $recordsFiltered;

    /**
     * @param FacadeInterface $workflowFunction
     */
    public function addWorkflowFunction(FacadeInterface $workflowFunction)
    {
        $this->workflowFunctions[] = $workflowFunction;
    }
}
