<?php

namespace OpenOrchestra\WorkflowFunction\Model;

use Doctrine\Common\Collections\Collection;
use OpenOrchestra\ModelInterface\Model\BlameableInterface;
use OpenOrchestra\ModelInterface\Model\TimestampableInterface;
use OpenOrchestra\ModelInterface\Model\RoleInterface;
use OpenOrchestra\ModelInterface\Model\TranslatedValueInterface;
use OpenOrchestra\ModelInterface\Model\TranslatedValueContainerInterface;

/**
 * Interface WorkflowFunctionInterface
 */
interface WorkflowFunctionInterface extends TimestampableInterface, BlameableInterface, TranslatedValueContainerInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @param TranslatedValueInterface $name
     */
    public function addName(TranslatedValueInterface $name);

    /**
     * @param TranslatedValueInterface $name
     */
    public function removeName(TranslatedValueInterface $name);

    /**
     * @param string $language
     *
     * @return string
     */
    public function getName();

    /**
     * @return ArrayCollection
     */
    public function getNames();

    /**
     * @return Collection
     */
    public function getRoles();

    /**
     * @param RoleInterface $role
     */
    public function addRole(RoleInterface $role);

    /**
     * @param RoleInterface $role
     */
    public function removeRole(RoleInterface $role);
}
