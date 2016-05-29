<?php

namespace OpenOrchestra\WorkflowFunctionModelBundle\Repository;

use OpenOrchestra\Repository\AbstractAggregateRepository;
use OpenOrchestra\WorkflowFunction\Repository\WorkflowRightRepositoryInterface;

/**
 * Class WorkflowRightRepositoryInterface
 */
class WorkflowRightRepository extends AbstractAggregateRepository implements WorkflowRightRepositoryInterface
{
    /**
     * @param string $userId
     *
     * @return \OpenOrchestra\WorkflowFunction\Model\WorkflowRightInterface
     */
    public function findOneByUserId($userId)
    {
        $qb = $this->createQueryBuilder();
        $qb->field('userId')->equals($userId);

        return $qb->getQuery()->getSingleResult();
    }
}
