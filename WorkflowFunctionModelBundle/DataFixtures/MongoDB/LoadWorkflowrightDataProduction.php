<?php

namespace OpenOrchestra\WorkflowFunctionModelBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OpenOrchestra\ModelInterface\DataFixtures\OrchestraProductionFixturesInterface;
use OpenOrchestra\WorkflowFunction\Model\WorkflowRightInterface;
use OpenOrchestra\WorkflowFunctionModelBundle\Document\Authorization;
use OpenOrchestra\WorkflowFunctionModelBundle\Document\WorkflowRight;

/**
 * Class LoadWorkflowRightDataProduction
 */
class LoadWorkflowRightDataProduction extends AbstractFixture implements OrderedFixtureInterface, OrchestraProductionFixturesInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $workflowRight = new WorkflowRight();
        $workflowRight->setUserId($this->getReference('user-admin')->getId());

        $authorization = new Authorization();
        $authorization->setReferenceId(WorkflowRightInterface::NODE);
        $authorization->addWorkflowFunction($this->getReference('workflow_function_validator_production'));

        $workflowRight->addAuthorization($authorization);

        $manager->persist($workflowRight);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1010;
    }
}