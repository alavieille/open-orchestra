<?php
namespace OpenOrchestra\WorkflowFunctionAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use OpenOrchestra\Backoffice\Manager\TranslationChoiceManager;

/**
 * Class OrchestraWorkflowFunctionType
 */
class OrchestraWorkflowFunctionType extends AbstractType
{
    protected $workflowFunctionClass;
    protected $translationChoiceManager;

    /**
     * @param string                   $workflowFunctionClass
     * @param TranslationChoiceManager $translationChoiceManager
     */
    public function __construct($workflowFunctionClass, TranslationChoiceManager $translationChoiceManager)
    {
        $this->workflowFunctionClass = $workflowFunctionClass;
        $this->translationChoiceManager = $translationChoiceManager;
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $translationChoiceManager = $this->translationChoiceManager;
        $resolver->setDefaults(
            array(
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'class' => $this->workflowFunctionClass,
                'choice_label' => function ($choice) use ($translationChoiceManager) {
                    return $translationChoiceManager->choose($choice->getNames());
                },
            )
        );
    }

    /**
     * Returns the name of the parent type.
     *
     * You can also return a type instance from this method, although doing so
     * is discouraged because it leads to a performance penalty. The support
     * for returning type instances may be dropped from future releases.
     *
     * @return string|null|FormTypeInterface The name of the parent type if any, null otherwise.
     */
    public function getParent()
    {
        return 'document';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'orchestra_workflow_function';
    }
}
