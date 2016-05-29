<?php

namespace OpenOrchestra\WorkflowFunctionAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use OpenOrchestra\Backoffice\EventListener\TranslateValueInitializerListener;
use Symfony\Component\Form\FormEvents;

/**
 * Class WorkflowFunctionType
 */
class WorkflowFunctionType extends AbstractType
{
    protected $workflowFunctionClass;
    protected $translateValueInitializer;

    /**
     * @param string                            $workflowFunctionClass
     * @param TranslateValueInitializerListener $translateValueInitializer
     */
    public function __construct(
        $workflowFunctionClass,
        TranslateValueInitializerListener $translateValueInitializer
    )
    {
        $this->translateValueInitializer = $translateValueInitializer;
        $this->workflowFunctionClass = $workflowFunctionClass;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this->translateValueInitializer, 'preSetData'));
        $builder
            ->add('names', 'oo_translated_value_collection', array(
                'label' => 'open_orchestra_workflow_function_admin.form.workflow_function.name'
            ))
            ->add('roles', 'oo_workflow_role_choice', array(
                'label' => 'open_orchestra_workflow_function_admin.form.workflow_function.role',
                'multiple' => true,
            ));
        if (array_key_exists('disabled', $options)) {
            $builder->setAttribute('disabled', $options['disabled']);
        }
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->workflowFunctionClass,
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'oo_workflow_function';
    }
}
