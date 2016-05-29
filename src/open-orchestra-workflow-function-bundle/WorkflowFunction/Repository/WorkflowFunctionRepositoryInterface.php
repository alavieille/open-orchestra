<?php

namespace OpenOrchestra\WorkflowFunction\Repository;

use Doctrine\Common\Collections\Collection;
use OpenOrchestra\ModelInterface\Model\RoleInterface;
use OpenOrchestra\Pagination\Configuration\FinderConfiguration;
use OpenOrchestra\Pagination\Configuration\PaginateFinderConfiguration;
use OpenOrchestra\ModelInterface\Repository\RoleableElementRepositoryInterface;

/**
 * Interface WorkflowFunctionRepositoryInterface
 */
interface WorkflowFunctionRepositoryInterface extends RoleableElementRepositoryInterface
{
    /**
     * @param PaginateFinderConfiguration $configuration
     *
     * @return mixed
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function findForPaginate(PaginateFinderConfiguration $configuration);

    /**
     * @return int
     */
    public function count();

    /**
     * @param FinderConfiguration $configuration
     *
     * @return mixed
     */
    public function countWithFilter(FinderConfiguration $configuration);

    /**
     * @return Collection
     */
    public function findAllWorkflowFunction();

    /**
     * @param string $id
     *
     * @return mixed
     */
    public function find($id);

    /**
     * @param RoleInterface $role
     *
     * @return Collection
     */
    public function findByRole(RoleInterface $role);
}
