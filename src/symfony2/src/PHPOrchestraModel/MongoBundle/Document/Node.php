<?php

/*
 * Business & Decision - Commercial License
 *
 * Copyright 2014 Business & Decision.
 *
 * All rights reserved. You CANNOT use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell this Software or any parts of this
 * Software, without the written authorization of Business & Decision.
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * See LICENSE.txt file for the full LICENSE text.
 */

namespace PHPOrchestraModel\MongoBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Description of Node
 *
 * @author Nicolas BOUQUET <nicolas.bouquet@businessdecision.com>
 * @MongoDB\Document(
 *   collection="node",
 *   repositoryClass="PHPOrchestraModel\MongoBundle\Repository\NodeRepository"
 * )
 */
class Node extends \PHPOrchestra\ModelBundle\Document\BaseNode
{
}
