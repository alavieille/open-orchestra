<?php

namespace OpenOrchestra\WorkflowFunctionAdminBundle\Tests\Manager;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use OpenOrchestra\WorkflowFunctionAdminBundle\Manager\WorkflowRightManager;
use Phake;

/**
 * Class WorkflowRightManagerTest
 */
class WorkflowRightManagerTest extends AbstractBaseTestCase
{
    /**
     * @var WorkflowRightManager
     */
    protected $workflowRightManager;

    protected $contentTypeRepository;
    protected $workflowRightRepository;
    protected $authorizationWorkflowRightManager;
    protected $workflowRightClass = 'OpenOrchestra\WorkflowFunctionModelBundle\Document\WorkflowRight';
    protected $referenceClass = 'OpenOrchestra\WorkflowFunctionModelBundle\Document\Reference';

    protected $authorizationClass = 'OpenOrchestra\WorkflowFunctionModelBundle\Document\Authorization';

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->contentTypeRepository = Phake::mock('OpenOrchestra\ModelInterface\Repository\ContentTypeRepositoryInterface');
        $this->workflowRightRepository = Phake::mock('OpenOrchestra\WorkflowFunction\Repository\WorkflowRightRepositoryInterface');
        $this->authorizationWorkflowRightManager = Phake::mock('OpenOrchestra\WorkflowFunction\Manager\AuthorizationWorkflowRightManager');

        $this->workflowRightManager = new WorkflowRightManager($this->contentTypeRepository, $this->workflowRightRepository, $this->authorizationWorkflowRightManager, $this->workflowRightClass, $this->referenceClass);
    }

    /**
     * test loadOrGenerateByUser
     */
    public function testLoadOrGenerateByUser()
    {

        $userId = 'fakeUserId';

        $this->workflowRightManager->loadOrGenerateByUser($userId);

        Phake::verify($this->contentTypeRepository, Phake::times(1))->findAllNotDeletedInLastVersion();
        Phake::verify($this->workflowRightRepository, Phake::times(1))->findOneByUserId($userId);
        Phake::verify($this->authorizationWorkflowRightManager, Phake::times(1))->cleanAuthorization(Phake::anyParameters());
    }
}
