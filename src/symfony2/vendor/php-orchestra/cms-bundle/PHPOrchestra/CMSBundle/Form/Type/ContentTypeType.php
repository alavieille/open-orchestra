<?php
/**
 * This file is part of the PHPOrchestra\CMSBundle.
 *
 * @author Noël Gilain <noel.gilain@businessdecision.com>
 */

namespace PHPOrchestra\CMSBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContentTypeType extends AbstractType
{
    public function __construct()
    {
        
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Nom du type de contenu : '))
            ->add('contentTypeId', 'text', array('label' => 'Identifiant du type de contenu : '))
            ->add('version', 'text', array('read_only' => true))
            ->add('status', 'text', array('read_only' => true))
            ->add('enregistrer', 'submit');
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'contentType';
    }
}