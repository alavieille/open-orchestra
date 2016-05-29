<?php

namespace OpenOrchestra\WorkflowFunctionAdminBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use OpenOrchestra\WorkflowFunctionAdminBundle\NavigationPanel\Strategies\WorkflowFunctionPanelStrategy;

/**
 * Class AbstractLoadGroupData
 */
abstract class AbstractLoadGroupData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param $groupName
     */
    protected function addRole($groupName)
    {
        $group = $this->getReference($groupName);
        $group->addRole(WorkflowFunctionPanelStrategy::ROLE_ACCESS_WORKFLOWFUNCTION);
        $group->addRole(WorkflowFunctionPanelStrategy::ROLE_ACCESS_CREATE_WORKFLOWFUNCTION);
        $group->addRole(WorkflowFunctionPanelStrategy::ROLE_ACCESS_UPDATE_WORKFLOWFUNCTION);
        $group->addRole(WorkflowFunctionPanelStrategy::ROLE_ACCESS_DELETE_WORKFLOWFUNCTION);
    }
}
