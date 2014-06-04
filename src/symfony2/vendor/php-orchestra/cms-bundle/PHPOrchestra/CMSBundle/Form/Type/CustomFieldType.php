<?php
/**
 * This file is part of the PHPOrchestra\CMSBundle.
 *
 * @author Noël Gilain <noel.gilain@businessdecision.com>
 */

namespace PHPOrchestra\CMSBundle\Form\Type;

use PHPOrchestra\CMSBundle\Form\DataTransformer\CustomFieldTransformer;
use PHPOrchestra\CMSBundle\Exception\UnknownFieldTypeException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CustomFieldType extends AbstractType
{
    protected $availableFields = null;

    public function __construct(ContainerInterface $container)
    {
        $this->availableFields = $container->getParameter('php_orchestra.custom_types');
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new CustomFieldTransformer();
        $builder->addModelTransformer($transformer);
        
        $builder->add('label', 'text')
            ->add('fieldId', 'text')
            ->add('searchable', 'checkbox', array('required' => false))
            ->add('removeField', 'checkbox', array('required' => false));
        
        if (!isset($this->availableFields[$options['data']->type])) {
            throw new UnknownFieldTypeException('Unknown field type : ' . $options['data']->type);
        }
        
        $parameters = $this->availableFields[$options['data']->type];
        
        $optionsValues = (object) array();
        if (isset($options['data']->options)) {
            $optionsValues = $options['data']->options;
        } else {
            $options['data']->options = $optionsValues;
        }
        
        foreach ($parameters['options'] as $optionName => $option) {
            if (!property_exists($optionsValues, $optionName)) {
                $options['data']->options->$optionName = $option['default_value'];
            }
            $fieldParams = array(
                'label' => $option['label'],
                'required' => $option['required']
            );
            
            $builder->add('option_' . $optionName, $option['type'], $fieldParams);
        }
    }

    /**
     * getName
     */
    public function getName()
    {
        return 'orchestra_customField';
    }
}
