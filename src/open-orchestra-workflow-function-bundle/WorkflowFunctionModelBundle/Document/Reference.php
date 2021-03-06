<?php

namespace OpenOrchestra\WorkflowFunctionModelBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use OpenOrchestra\WorkflowFunction\Model\ReferenceInterface;

/**
 * Description of Reference
 *
 * @ODM\EmbeddedDocument
 */
class Reference implements ReferenceInterface
{
    /**
     * @var string $id
     */
    protected $id;

    /**
     * Set id
     *
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
