<?php

namespace OpenOrchestra\WorkflowFunctionAdminBundle\Security\Authorization\Voter;

use OpenOrchestra\WorkflowFunction\Model\WorkflowFunctionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use OpenOrchestra\ModelInterface\Repository\ContentTypeRepositoryInterface;
use OpenOrchestra\ModelInterface\Model\ContentInterface;
use OpenOrchestra\ModelInterface\Model\BlameableInterface;
use OpenOrchestra\WorkflowFunction\Repository\WorkflowRightRepositoryInterface;
use OpenOrchestra\WorkflowFunction\Model\WorkflowRightInterface;
use FOS\UserBundle\Model\UserInterface;

/**
 * Class WorkflowStateVoter
 */
class WorkflowStateVoter implements VoterInterface
{
    protected $workflowRightRepository;
    protected $contentTypeRepository;

    /**
     * @param WorkflowRightRepositoryInterface $workflowRightRepository
     * @param ContentTypeRepositoryInterface   $contentTypeRepository
     */
    public function __construct(WorkflowRightRepositoryInterface $workflowRightRepository, ContentTypeRepositoryInterface $contentTypeRepository)
    {
        $this->workflowRightRepository = $workflowRightRepository;
        $this->contentTypeRepository = $contentTypeRepository;
    }

    /**
     * Checks if the voter supports the given attribute.
     *
     * @param string $attribute An attribute
     *
     * @return bool true if this Voter supports the attribute, false otherwise
     */
    public function supportsAttribute($attribute)
    {
        return 0 === strpos($attribute, 'ROLE_ACCESS_UPDATE_NODE') ||
               0 === strpos($attribute, 'ROLE_ACCESS_UPDATE_ERROR_NODE') ||
               0 === strpos($attribute, 'ROLE_ACCESS_UPDATE_CONTENT_TYPE_FOR_CONTENT');
    }

    /**
     * Checks if the voter supports the given class.
     *
     * @param string $class A class name
     *
     * @return bool true if this Voter can process the class
     */
    public function supportsClass($class)
    {
        return is_subclass_of($class, 'OpenOrchestra\ModelInterface\Model\StatusableInterface');
    }

    /**
     * Returns the vote for the given parameters.
     *
     * This method must return one of the following constants:
     * ACCESS_GRANTED, ACCESS_DENIED, or ACCESS_ABSTAIN.
     *
     * @param TokenInterface $token      A TokenInterface instance
     * @param object|null    $object     The object to secure
     * @param array          $attributes An array of attributes associated with the method being invoked
     *
     * @return int either ACCESS_GRANTED, ACCESS_ABSTAIN, or ACCESS_DENIED
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $user = $token->getUser();
        $isOrchestraUser = $user instanceof UserInterface;
        if ($isOrchestraUser && $user->isSuperAdmin()) {
            return self::ACCESS_GRANTED;
        }
        if (!$this->supportsClass($object)) {
            return self::ACCESS_ABSTAIN;
        }
        foreach ($attributes as $role) {
            if (!$this->supportsAttribute($role)) {
                return self::ACCESS_ABSTAIN;
            }
        }
        if ($isOrchestraUser) {
            $isOwner = $object instanceof BlameableInterface && ($object->getCreatedBy() == $user->getUsername());
            $workflowRight = $this->workflowRightRepository->findOneByUserId($user->getId());
            if (null === $workflowRight) {
                return VoterInterface::ACCESS_DENIED;
            }
            $referenceId = WorkflowRightInterface::NODE;
            if ($object instanceof ContentInterface) {
                $contentType = $this->contentTypeRepository->findOneByContentTypeIdInLastVersion($object->getContentType());
                $referenceId = $contentType->getId();
            }
            $authorizations = $workflowRight->getAuthorizations();
            foreach ($authorizations as $authorization) {
                if ($authorization->getReferenceId() == $referenceId) {
                    if (!$authorization->isOwner() || ($authorization->isOwner() && true === $isOwner)) {
                        $workflowFunctions = $authorization->getWorkflowFunctions();
                        /** @var WorkflowFunctionInterface $workflowFunction */
                        foreach ($workflowFunctions as $workflowFunction) {
                            $statusFromRole = $object->getStatus()->getFromRoles()->toArray();
                            $statusToRole = $object->getStatus()->getToRoles()->toArray();
                            $workflowRoles = $workflowFunction->getRoles()->toArray();
                            if (
                                $this->hasIntersectArray($statusFromRole, $workflowRoles) ||
                                $this->hasIntersectArray($statusToRole, $workflowRoles)
                            ) {
                                return VoterInterface::ACCESS_GRANTED;
                            } else {
                                return VoterInterface::ACCESS_DENIED;
                            }
                        }
                    }

                    return VoterInterface::ACCESS_DENIED;
                }
            }
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }

    /**
     * @param array $array1
     * @param array $array2
     *
     * @return bool
     */
    protected function hasIntersectArray(array $array1, array $array2)
    {
        foreach ($array1 as $value) {
            if (in_array($value, $array2)) {
                return true;
            }
        }

        return false;
    }
}
