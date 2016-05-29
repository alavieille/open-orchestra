<?php

namespace OpenOrchestra\WorkflowFunction\Event;

use OpenOrchestra\WorkflowFunction\Model\WorkflowFunctionInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class WorkflowFunctionEvent
 */
class WorkflowFunctionEvent extends Event
{
    protected $workflowFunction;

    /**
     * @param WorkflowFunctionInterface $workflowFunction
     */
    public function __construct(WorkflowFunctionInterface $workflowFunction)
    {
        $this->workflowFunction = $workflowFunction;
    }

    /**
     * @return WorkflowFunctionInterface
     */
    public function getWorkflowFunction()
    {
        return $this->workflowFunction;
    }
}
