<?php

namespace OpenOrchestra\WorkflowFunctionModelBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Blameable\Traits\BlameableDocument;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use OpenOrchestra\WorkflowFunction\Model\WorkflowFunctionInterface;
use OpenOrchestra\ModelInterface\Model\RoleInterface;
use OpenOrchestra\Mapping\Annotations as ORCHESTRA;
use OpenOrchestra\ModelInterface\Model\TranslatedValueInterface;

/**
 * Class WorkflowFunction
 *
 * @ODM\Document(
 *   collection="workflow_function",
 *   repositoryClass="OpenOrchestra\WorkflowFunctionModelBundle\Repository\WorkflowFunctionRepository"
 * )
 */
class WorkflowFunction implements WorkflowFunctionInterface
{
    use BlameableDocument;
    use TimestampableDocument;

    /**
     * @var string $id
     *
     * @ODM\Id
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ODM\EmbedMany(targetDocument="OpenOrchestra\ModelInterface\Model\TranslatedValueInterface", strategy="set")
     * @ORCHESTRA\Search(key="name", type="translatedValue")
     */
    protected $names;

    /**
     * @var Collection
     *
     * @ODM\ReferenceMany(targetDocument="OpenOrchestra\ModelInterface\Model\RoleInterface")
     */
    protected $roles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initCollections();
    }

    /**
     * Clone the element
     */
    public function __clone()
    {
        $this->initCollections();
    }

    protected function initCollections() {
        $this->roles = new ArrayCollection();
        $this->names = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param TranslatedValueInterface $name
     */
    public function addName(TranslatedValueInterface $name)
    {
        $this->names->set($name->getLanguage(), $name);
    }

    /**
     * @param TranslatedValueInterface $name
     */
    public function removeName(TranslatedValueInterface $name)
    {
        $this->names->remove($name->getLanguage());
    }

    /**
     * @param string $language
     *
     * @return string
     */
    public function getName($language = 'en')
    {
        return $this->names->get($language)->getValue();
    }

    /**
     * @return ArrayCollection
     */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * @return Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param RoleInterface $role
     */
    public function addRole(RoleInterface $role)
    {
        $this->roles->add($role);
    }

    /**
     * @param RoleInterface $role
     */
    public function removeRole(RoleInterface $role)
    {
        $this->roles->remove($role);
    }

    /**
     * @return array
     */
    public function getTranslatedProperties()
    {
        return array(
            'getNames'
        );
    }
}
