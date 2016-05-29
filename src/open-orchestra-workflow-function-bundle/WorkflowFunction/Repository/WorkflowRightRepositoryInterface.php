<?php

namespace OpenOrchestra\WorkflowFunction\Repository;

/**
 * Interface WorkflowFunctionRepositoryInterface
 */
interface WorkflowRightRepositoryInterface
{
    /**
     * @param string $userId
     *
     * @return  \OpenOrchestra\WorkflowFunction\Model\WorkflowRightInterface
     */
    public function findOneByUserId($userId);

    /**
     * @param string $id
     *
     * @return mixed
     */
    public function find($id);
}
