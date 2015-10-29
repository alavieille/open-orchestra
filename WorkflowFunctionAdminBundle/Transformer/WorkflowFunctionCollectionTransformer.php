<?php

namespace OpenOrchestra\WorkflowFunctionAdminBundle\Transformer;

use Doctrine\Common\Collections\Collection;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractSecurityCheckerAwareTransformer;
use OpenOrchestra\WorkflowFunctionAdminBundle\Facade\WorkflowFunctionCollectionFacade;
use OpenOrchestra\WorkflowFunctionAdminBundle\NavigationPanel\Strategies\WorkflowFunctionPanelStrategy;

/**
 * Class WorkflowFunctionCollectionTransformer
 */
class WorkflowFunctionCollectionTransformer extends AbstractSecurityCheckerAwareTransformer
{
    /**
     * @param Collection $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $facade = new WorkflowFunctionCollectionFacade();

        foreach ($mixed as $workflowFunction) {
            $facade->addWorkflowFunction($this->getTransformer('workflow_function')->transform($workflowFunction));
        }

        if ($this->authorizationChecker->isGranted(WorkflowFunctionPanelStrategy::ROLE_ACCESS_CREATE_WORKFLOWFUNCTION)) {
            $facade->addLink('_self_add', $this->generateRoute(
                'open_orchestra_backoffice_workflow_function_new',
                array()
            ));
        }

        return $facade;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'workflow_function_collection';
    }
}
