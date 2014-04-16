<?php
/**
 * This file is part of the PHPOrchestra\CMSBundle.
 *
 * @author Noël Gilain <noel.gilain@businessdecision.com>
 */

namespace PHPOrchestra\CMSBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use PHPOrchestra\CMSBundle\Form\DataTransformer\JsonToBlocksTransformer;

class BlocksType extends AbstractType
{
    /**
     * documentLoader service
     * @var documentLoader
     */
    protected $documentLoader = null;

    
    /**
     * Constructor, require documentLoader service
     * 
     * @param $documentLoader
     */
    public function __construct($documentLoader)
    {
        $this->documentLoader = $documentLoader;
    }
    
    
    /**
     * Form builder
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new JsonToBlocksTransformer($this->documentLoader);
        $builder->addModelTransformer($transformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'dialogPath' => '',
                'js' => array(),
                'objects' => array(),
                'attr' => array('class' => 'not-mapped')
            )
        );
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['dialogPath'] = $options['dialogPath'];
        $view->vars['js'] = $options['js'];
        $view->vars['objects'] = $options['objects'];
    }
    
    
    /**
     * Extends textarea type
     */
    public function getParent()
    {
        return 'hidden';
    }

    /**
     * getName
     */
    public function getName()
    {
        return 'orchestra_blocks';
    }
}
