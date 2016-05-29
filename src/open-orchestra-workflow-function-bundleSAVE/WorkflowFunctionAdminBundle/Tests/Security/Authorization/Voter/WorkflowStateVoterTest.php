<?php

namespace OpenOrchestra\WorkflowFunctionAdminBundle\Tests\Security\Authorization\Voter;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use OpenOrchestra\WorkflowFunctionAdminBundle\Security\Authorization\Voter\WorkflowStateVoter;
use Phake;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Test WorkflowStateVoterTest
 */
class WorkflowStateVoterTest extends AbstractBaseTestCase
{
    protected $workflowRightRepository;
    protected $contentTypeRepository;
    protected $contentType = 'fakeContentType';
    protected $username = 'fakeUsername';
    protected $token;
    protected $user;

    /**
     * @var WorkflowStateVoter
     */
    protected $voter;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $contentType = Phake::mock('OpenOrchestra\ModelInterface\Model\ContentTypeInterface');
        Phake::when($contentType)->getId()->thenReturn($this->contentType);

        $this->contentTypeRepository = Phake::mock('OpenOrchestra\ModelInterface\Repository\ContentTypeRepositoryInterface');
        Phake::when($this->contentTypeRepository)->findOneByContentTypeIdInLastVersion(Phake::anyParameters())->thenReturn($contentType);

        $this->workflowRightRepository = Phake::mock('OpenOrchestra\WorkflowFunction\Repository\WorkflowRightRepositoryInterface');

        $this->user = Phake::mock('OpenOrchestra\UserBundle\Document\User');
        Phake::when($this->user)->getId()->thenReturn('fakeUserId');
        Phake::when($this->user)->getUsername()->thenReturn($this->username);

        $this->token = Phake::mock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        Phake::when($this->token)->getUser()->thenReturn($this->user);

        $this->voter = new WorkflowStateVoter($this->workflowRightRepository, $this->contentTypeRepository);
    }

    /**
     * Test instance
     */
    public function testInstance()
    {
        $this->assertInstanceOf('Symfony\Component\Security\Core\Authorization\Voter\VoterInterface', $this->voter);
    }

    /**
     * @param string  $class
     * @param boolean $isSupported
     *
     * @dataProvider provideClassName
     */
    public function testSupportsClass($class, $isSupported)
    {
        $this->assertEquals($isSupported, $this->voter->supportsClass($class));
    }

    /**
     * @return array
     */
    public function provideClassName()
    {
        return array(
            array(Phake::mock('OpenOrchestra\ModelInterface\Model\ContentTypeInterface'), false),
            array(Phake::mock('stdClass'), false),
            array(Phake::mock('OpenOrchestra\ModelInterface\Model\ContentInterface'), true),
            array(Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface'), true)
        );
    }

    /**
     * @param string $attribute
     * @param bool   $supports
     *
     * @dataProvider provideAttributeAndSupport
     */
    public function testSupportsAttribute($attribute, $supports)
    {
        $this->assertSame($supports, $this->voter->supportsAttribute($attribute));
    }

    /**
     * @return array
     */
    public function provideAttributeAndSupport()
    {
        return array(
            array('test', false),
            array('ROLE_ACCESS', false),
            array('ROLE_', false),
            array('5640af7a02b0cf39178b4598', false),
            array('ROLE_ACCESS_CREATE_CONTENT', false),
            array('ROLE_ACCESS_CREATE_NODE', false),
            array('ROLE_ACCESS_CONTENT_TYPE_FOR_CONTENT', false),
            array('ROLE_ACCESS_UPDATE_NODE', true),
            array('ROLE_ACCESS_UPDATE_ERROR_NODE', true),
            array('ROLE_ACCESS_UPDATE_CONTENT_TYPE_FOR_CONTENT', true),
        );
    }

    /**
     * @param string $accessResponse
     * @param mixed  $object
     * @param string $referenceId
     * @param array  $attributes
     * @param array  $workflowRoles
     * @param bool   $isOwner
     * @param bool   $superAdmin
     *
     * @dataProvider provideObject
     */
    public function testVote($accessResponse, $object, $referenceId, array $attributes, array $workflowRoles, $isOwner = false, $superAdmin = false)
    {
        $workflowRight = Phake::mock('OpenOrchestra\WorkflowFunction\Model\WorkflowRightInterface');
        $authorizations = new ArrayCollection();

        $authorization = Phake::mock('OpenOrchestra\WorkflowFunction\Model\AuthorizationInterface');
        Phake::when($authorization)->getReferenceId()->thenReturn($referenceId);
        Phake::when($authorization)->isOwner()->thenReturn($isOwner);
        $authorizations->add($authorization);

        $workflowFunctions = new ArrayCollection();
        $workflowFunction = Phake::mock('OpenOrchestra\WorkflowFunction\Model\WorkflowFunctionInterface');
        $roleArrayCollection = Phake::mock('Doctrine\Common\Collections\ArrayCollection');
        Phake::when($workflowFunction)->getRoles()->thenReturn($roleArrayCollection);
        Phake::when($roleArrayCollection)->toArray()->thenReturn($workflowRoles);
        $workflowFunctions->add($workflowFunction);

        Phake::when($authorization)->getWorkflowFunctions()->thenReturn($workflowFunctions);

        Phake::when($workflowRight)->getAuthorizations()->thenReturn($authorizations);

        Phake::when($this->user)->isSuperAdmin()->thenReturn($superAdmin);

        Phake::when($this->workflowRightRepository)->findOneByUserId(Phake::anyParameters())->thenReturn($workflowRight);
        $this->assertEquals($accessResponse, $this->voter->vote($this->token, $object, $attributes));
    }

    /**
     * @return array
     */
    public function provideObject()
    {
        $rolePublishToDraft = Phake::mock('OpenOrchestra\ModelInterface\Model\RoleInterface');
        $roleTranslateToPublish = Phake::mock('OpenOrchestra\ModelInterface\Model\RoleInterface');
        $roleDraftToPending = Phake::mock('OpenOrchestra\ModelInterface\Model\RoleInterface');
        $rolePendingToTranslate = Phake::mock('OpenOrchestra\ModelInterface\Model\RoleInterface');

        $status1 = Phake::mock('OpenOrchestra\ModelInterface\Model\StatusInterface');
        $arrayCollectionDraftToPending = Phake::mock('Doctrine\Common\Collections\ArrayCollection');
        $arrayCollectionPendingToTranslate = Phake::mock('Doctrine\Common\Collections\ArrayCollection');
        Phake::when($status1)->getFromRoles()->thenReturn($arrayCollectionDraftToPending);
        Phake::when($status1)->getToRoles()->thenReturn($arrayCollectionPendingToTranslate);
        Phake::when($arrayCollectionDraftToPending)->toArray()->thenReturn(array($roleDraftToPending));
        Phake::when($arrayCollectionPendingToTranslate)->toArray()->thenReturn(array($rolePendingToTranslate));

        $status2 = Phake::mock('OpenOrchestra\ModelInterface\Model\StatusInterface');
        $arrayCollectionPublishToDraft = Phake::mock('Doctrine\Common\Collections\ArrayCollection');
        $arrayCollectionTranslateToPublish = Phake::mock('Doctrine\Common\Collections\ArrayCollection');
        Phake::when($status2)->getFromRoles()->thenReturn($arrayCollectionTranslateToPublish);
        Phake::when($status2)->getToRoles()->thenReturn($arrayCollectionPublishToDraft);
        Phake::when($arrayCollectionPublishToDraft)->toArray()->thenReturn(array($rolePublishToDraft));
        Phake::when($arrayCollectionTranslateToPublish)->toArray()->thenReturn(array($roleTranslateToPublish));

        $workflowRolesContributor = array($roleDraftToPending);
        $workflowRolesValidator = array($roleTranslateToPublish, $rolePublishToDraft);
        $workflowRolesTranslator = array($rolePendingToTranslate);

        $object0 = Phake::mock('OpenOrchestra\ModelInterface\Model\ContentInterface');
        Phake::when($object0)->getContentType()->thenReturn($this->contentType);
        Phake::when($object0)->getCreatedBy()->thenReturn($this->username);
        Phake::when($object0)->getStatus()->thenReturn($status2);

        $object1 = Phake::mock('OpenOrchestra\ModelInterface\Model\ContentInterface');
        Phake::when($object1)->getContentType()->thenReturn($this->contentType);
        Phake::when($object1)->getCreatedBy()->thenReturn($this->username);
        Phake::when($object1)->getStatus()->thenReturn($status1);

        $object2 = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        Phake::when($object2)->getCreatedBy()->thenReturn('fake_username');
        Phake::when($object2)->getStatus()->thenReturn($status1);

        $attributes = array('ROLE_ACCESS_UPDATE_CONTENT_TYPE_FOR_CONTENT');

        return array(
            array(VoterInterface::ACCESS_GRANTED, Phake::mock('stdClass'), 'fake_ref', array(), array(), false, true),
            array(VoterInterface::ACCESS_ABSTAIN, $object0, 'fake_reference_id', $attributes, array()),
            array(VoterInterface::ACCESS_ABSTAIN, $object0, 'fake_reference_id', array(), array()),
            array(VoterInterface::ACCESS_DENIED, $object0, $this->contentType, $attributes, $workflowRolesContributor),
            array(VoterInterface::ACCESS_DENIED, $object0, $this->contentType, $attributes, $workflowRolesTranslator),
            array(VoterInterface::ACCESS_GRANTED, $object0, $this->contentType, $attributes, $workflowRolesValidator),
            array(VoterInterface::ACCESS_GRANTED, $object1, $this->contentType, $attributes, $workflowRolesContributor),
            array(VoterInterface::ACCESS_GRANTED, $object1, $this->contentType, $attributes, $workflowRolesTranslator),
            array(VoterInterface::ACCESS_DENIED, $object1, $this->contentType, $attributes, $workflowRolesValidator),
            array(VoterInterface::ACCESS_GRANTED, $object1, $this->contentType, $attributes, $workflowRolesContributor, true),
            array(VoterInterface::ACCESS_GRANTED, $object1, $this->contentType, $attributes, $workflowRolesTranslator, true),
            array(VoterInterface::ACCESS_DENIED, $object1, $this->contentType, $attributes, $workflowRolesValidator, true),
            array(VoterInterface::ACCESS_GRANTED, $object2, 'open_orchestra_workflow_function.node', $attributes, $workflowRolesContributor),
            array(VoterInterface::ACCESS_GRANTED, $object2, 'open_orchestra_workflow_function.node', $attributes, $workflowRolesTranslator),
            array(VoterInterface::ACCESS_DENIED, $object2, 'open_orchestra_workflow_function.node', $attributes, $workflowRolesValidator),
            array(VoterInterface::ACCESS_DENIED, $object2, 'open_orchestra_workflow_function.node', $attributes, $workflowRolesContributor, true),
            array(VoterInterface::ACCESS_DENIED, $object2, 'open_orchestra_workflow_function.node', $attributes, $workflowRolesTranslator, true),
            array(VoterInterface::ACCESS_DENIED, $object2, 'open_orchestra_workflow_function.node', $attributes, $workflowRolesValidator, true),
        );
    }
}
