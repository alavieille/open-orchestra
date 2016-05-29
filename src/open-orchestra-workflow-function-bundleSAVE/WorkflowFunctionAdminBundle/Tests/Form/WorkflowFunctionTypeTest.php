<?php

namespace OpenOrchestra\WorkflowFunctionAdminBundle\Tests\Form\Type;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use OpenOrchestra\WorkflowFunctionAdminBundle\Form\Type\WorkflowFunctionType;
use Phake;

/**
 * Description of WorkflowFunctionTypeTest
 */
class WorkflowFunctionTypeTest extends AbstractBaseTestCase
{
    protected $workflowFunctionClass = 'fakeClass';
    protected $workflowFunctionType;
    protected $translateValueInitializer;
    protected $roles;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->translateValueInitializer = Phake::mock('OpenOrchestra\Backoffice\EventListener\TranslateValueInitializerListener');

        $this->workflowFunctionType = new WorkflowFunctionType($this->workflowFunctionClass, $this->translateValueInitializer);
    }

    /**
     * test default options
     */
    public function testSetDefaultOptions()
    {
        $resolverMock = Phake::mock('Symfony\Component\OptionsResolver\OptionsResolver');

        $this->workflowFunctionType->configureOptions($resolverMock);

        Phake::verify($resolverMock)->setDefaults(
            array('data_class' => $this->workflowFunctionClass)
        );
    }

    /**
     * test buildForm
     */
    public function testBuildForm()
    {
        $formBuilderInterface = Phake::mock('Symfony\Component\Form\FormBuilderInterface');
        Phake::when($formBuilderInterface)->add(Phake::anyParameters())->thenReturn($formBuilderInterface);

        $this->workflowFunctionType->buildForm($formBuilderInterface, array());

        Phake::verify($formBuilderInterface)->add('names', 'oo_translated_value_collection', array(
            'label' => 'open_orchestra_workflow_function_admin.form.workflow_function.name'
        ));
        Phake::verify($formBuilderInterface)->add('roles', 'oo_workflow_role_choice', array(
            'label' => 'open_orchestra_workflow_function_admin.form.workflow_function.role',
            'multiple' => true
        ));
    }

    /**
     * test Name
     */
    public function testGetName()
    {
        $this->assertEquals('oo_workflow_function', $this->workflowFunctionType->getName());
    }
}
