<?php

namespace OpenOrchestra\WorkflowFunctionBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OpenOrchestra\WorkflowFunctionModelBundle\Document\WorkflowFunction;
use OpenOrchestra\ModelInterface\DataFixtures\OrchestraFunctionalFixturesInterface;
use OpenOrchestra\ModelBundle\Document\TranslatedValue;

/**
 * Class LoadWorkflowFunctionDataFunctional
 */
class LoadWorkflowFunctionDataFunctional extends AbstractFixture implements OrderedFixtureInterface, OrchestraFunctionalFixturesInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $enName = $this->generateTranslatedValue('en', 'Validator');
        $frName = $this->generateTranslatedValue('fr', 'Validateur');
        $workflowFunctionValidator = new WorkflowFunction();
        $workflowFunctionValidator->addName($enName);
        $workflowFunctionValidator->addName($frName);
        $workflowFunctionValidator->addRole($this->getReference('role-functional-draft-to-pending'));
        $workflowFunctionValidator->addRole($this->getReference('role-functional-pending-to-published'));
        $this->addReference('workflow-function-validator-functional', $workflowFunctionValidator);

        $enName = $this->generateTranslatedValue('en', 'Contributor');
        $frName = $this->generateTranslatedValue('fr', 'Contributeur');
        $workflowFunctionContributor = new WorkflowFunction();
        $workflowFunctionContributor->addName($enName);
        $workflowFunctionContributor->addName($frName);
        $workflowFunctionContributor->addRole($this->getReference('role-functional-published-to-draft'));
        $this->addReference('workflow-function-contributor-functional', $workflowFunctionContributor);

        $manager->persist($workflowFunctionValidator);
        $manager->persist($workflowFunctionContributor);
        $manager->flush();
    }

    /**
     * Generate a translatedValue
     *
     * @param string $language
     * @param string $value
     *
     * @return TranslatedValue
     */
    protected function generateTranslatedValue($language, $value)
    {
        $label = new TranslatedValue();
        $label->setLanguage($language);
        $label->setValue($value);

        return $label;
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 120;
    }
}

