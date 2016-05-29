<?php

namespace OpenOrchestra\WorkflowFunctionBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OpenOrchestra\ModelInterface\DataFixtures\OrchestraProductionFixturesInterface;
use OpenOrchestra\WorkflowFunctionModelBundle\Document\WorkflowFunction;
use OpenOrchestra\ModelBundle\Document\TranslatedValue;

/**
 * Class LoadWorkflowFunctionDataProduction
 */
class LoadWorkflowFunctionDataProduction extends AbstractFixture implements OrderedFixtureInterface, OrchestraProductionFixturesInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        if (false == $this->hasReference('workflow-function-validator-functional')) {
            $enName = $this->generateTranslatedValue('en', 'Validator');
            $frName = $this->generateTranslatedValue('fr', 'Validateur');
            $workflowFunctionValidator = new WorkflowFunction();
            $workflowFunctionValidator->addName($enName);
            $workflowFunctionValidator->addName($frName);
            $workflowFunctionValidator->addRole($this->getReference('role-production-draft-to-published'));
            $this->addReference('workflow-function-validator-production', $workflowFunctionValidator);

            $manager->persist($workflowFunctionValidator);
            $manager->flush();
        }
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
        return 125;
    }
}
