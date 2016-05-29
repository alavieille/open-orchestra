<?php

namespace OpenOrchestra\WorkflowFunctionAdminBundle\Controller\Api;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\Pagination\Configuration\PaginateFinderConfiguration;
use OpenOrchestra\WorkflowFunction\Event\WorkflowFunctionEvent;
use OpenOrchestra\WorkflowFunction\WorkflowFunctionEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use OpenOrchestra\BaseApiBundle\Controller\Annotation as Api;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenOrchestra\BaseApiBundle\Controller\BaseController;

/**
 * Class WorkflowFunctionController
 *
 * @Config\Route("workflow-function")
 *
 * @Api\Serialize()
 */
class WorkflowFunctionController extends BaseController
{
    /**
     * @param string $workflowFunctionId
     *
     * @Config\Route("/{workflowFunctionId}", name="open_orchestra_api_workflow_function_show")
     * @Config\Method({"GET"})
     *
     * @Config\Security("is_granted('ROLE_ACCESS_WORKFLOWFUNCTION')")
     *
     * @return FacadeInterface
     */
    public function showAction($workflowFunctionId)
    {
        $workflowFunction = $this->get('open_orchestra_workflow_function.repository.workflow_function')->find($workflowFunctionId);

        return $this->get('open_orchestra_api.transformer_manager')->get('workflow_function')->transform($workflowFunction);
    }

    /**
     * @param Request $request
     *
     * @Config\Route("", name="open_orchestra_api_workflow_functions_list")
     * @Config\Method({"GET"})
     *
     * @Config\Security("is_granted('ROLE_ACCESS_WORKFLOWFUNCTION')")
     *
     * @return FacadeInterface
     */
    public function listAction(Request $request)
    {
        $repository =  $this->get('open_orchestra_workflow_function.repository.workflow_function');

        $configuration = PaginateFinderConfiguration::generateFromRequest($request);
        $mapping = $this
            ->get('open_orchestra.annotation_search_reader')
            ->extractMapping($this->container->getParameter('open_orchestra_workflow_function.document.workflow_function.class'));
        $configuration->setDescriptionEntity($mapping);
        $workflowFunctionCollection = $repository->findForPaginate($configuration);
        $recordsTotal = $repository->count();
        $recordsFiltered = $repository->countWithFilter($configuration);

        $facade = $this->get('open_orchestra_api.transformer_manager')->get('workflow_function_collection')->transform($workflowFunctionCollection);
        $facade->recordsTotal = $recordsTotal;
        $facade->recordsFiltered = $recordsFiltered;

        return $facade;
    }

    /**
     * @param string $workflowFunctionId
     *
     * @Config\Route("/{workflowFunctionId}/delete", name="open_orchestra_api_workflow_function_delete")
     * @Config\Method({"DELETE"})
     *
     * @Config\Security("is_granted('ROLE_ACCESS_DELETE_WORKFLOWFUNCTION')")
     *
     * @return Response
     */
    public function deleteAction($workflowFunctionId)
    {
        $workflowFunction = $this->get('open_orchestra_workflow_function.repository.workflow_function')->find($workflowFunctionId);
        $dm = $this->get('object_manager');
        $this->dispatchEvent(WorkflowFunctionEvents::WORKFLOWFUNCTION_DELETE, new WorkflowFunctionEvent($workflowFunction));
        $dm->remove($workflowFunction);
        $dm->flush();

        return array();
    }
}
