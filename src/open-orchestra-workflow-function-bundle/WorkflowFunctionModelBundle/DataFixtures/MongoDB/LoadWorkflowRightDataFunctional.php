<?php

namespace OpenOrchestra\WorkflowFunctionModelBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OpenOrchestra\WorkflowFunction\Model\WorkflowRightInterface;
use OpenOrchestra\WorkflowFunctionModelBundle\Document\Authorization;
use OpenOrchestra\WorkflowFunctionModelBundle\Document\WorkflowRight;
use OpenOrchestra\ModelInterface\DataFixtures\OrchestraFunctionalFixturesInterface;

/**
 * Class LoadWorkflowRightDataFunctional
 */
class LoadWorkflowRightDataFunctional extends AbstractFixture implements OrderedFixtureInterface,OrchestraFunctionalFixturesInterface
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
        $authorization->addWorkflowFunction($this->getReference('workflow-function-validator-functional'));
        $authorization->addWorkflowFunction($this->getReference('workflow-function-contributor-functional'));

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
        return 1000;
    }
}
