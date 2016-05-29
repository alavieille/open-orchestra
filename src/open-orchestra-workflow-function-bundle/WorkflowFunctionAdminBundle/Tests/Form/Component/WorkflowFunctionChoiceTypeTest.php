<?php

namespace OpenOrchestra\WorkflowFunctionAdminBundle\Tests\Form\Type\Component;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use OpenOrchestra\WorkflowFunctionAdminBundle\Form\Type\Component\WorkflowFunctionChoiceType;
use Phake;

/**
 * Description of WorkflowFunctionChoiceTypeTest
 */
class WorkflowFunctionChoiceTypeTest extends AbstractBaseTestCase
{
    protected $workflowFunctionClass = 'fakeClass';
    protected $orchestraWorkflowFunction;
    protected $translationChoiceManager;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->translationChoiceManager = Phake::mock('OpenOrchestra\Backoffice\Manager\TranslationChoiceManager');
        $this->orchestraWorkflowFunction = new WorkflowFunctionChoiceType($this->workflowFunctionClass, $this->translationChoiceManager);
    }

    /**
     * test default options
     */
    public function testSetDefaultOptions()
    {
        $resolverMock = Phake::mock('Symfony\Component\OptionsResolver\OptionsResolver');

        $this->orchestraWorkflowFunction->configureOptions($resolverMock);
        $translationChoiceManager = $this->translationChoiceManager;

        Phake::verify($resolverMock)->setDefaults(
            array(
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'class' => $this->workflowFunctionClass,
                'choice_label' => function (WorkflowFunctionInterface $choice) use ($translationChoiceManager) {
                    return $translationChoiceManager->choose($choice->getNames());
                },
            )
        );
    }

    /**
     * Test parent
     */
    public function testGetParent()
    {
        $this->assertEquals('document', $this->orchestraWorkflowFunction->getParent());
    }

    /**
     * test Name
     */
    public function testGetName()
    {
        $this->assertEquals('oo_workflow_function_choice', $this->orchestraWorkflowFunction->getName());
    }
}
