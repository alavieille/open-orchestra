<?php

namespace OpenOrchestra\BaseApiBundle\DependencyInjection;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use OpenOrchestra\WorkflowFunctionModelBundle\DependencyInjection\OpenOrchestraWorkflowFunctionModelExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class OpenOrchestraWorkflowFunctionModelExtensionTest
 */
class OpenOrchestraWorkflowFunctionModelExtensionTest extends AbstractBaseTestCase
{
    /**
     * @param string      $file
     * @param string      $class
     * @param string      $name
     * @param string|null $repository
     * @param bool|false  $filterType
     *
     * @dataProvider provideDocumentClass
     */
    public function testConfig($file, $class, $name, $repository = null, $filterType = false)
    {
        $container = $this->loadContainerFromFile($file);
        $this->assertEquals($class, $container->getParameter('open_orchestra_workflow_function.document.' . $name . '.class'));
        if (null != $repository) {
            $this->assertDefinition($container->getDefinition('open_orchestra_workflow_function.repository.' . $name), $class, $repository, $filterType);
        }
    }

    /**
     * @return array
     */
    public function provideDocumentClass()
    {
        return array(
            array("empty", "OpenOrchestra\\WorkflowFunctionModelBundle\\Document\\WorkflowFunction", "workflow_function", "OpenOrchestra\\WorkflowFunctionModelBundle\\Repository\\WorkflowFunctionRepository", true),
            array("empty", "OpenOrchestra\\WorkflowFunctionModelBundle\\Document\\WorkflowRight", "workflow_right", "OpenOrchestra\\WorkflowFunctionModelBundle\\Repository\\WorkflowRightRepository"),
            array("empty", "OpenOrchestra\\WorkflowFunctionModelBundle\\Document\\Authorization", "authorization"),
            array("empty", "OpenOrchestra\\WorkflowFunctionModelBundle\\Document\\Reference", "reference"),
            array("value", "FakeClassWorkflowFunction", "workflow_function", "FakeRepositoryWorkflowFunction"),
            array("value", "FakeClassWorkflowRight", "workflow_right", "FakeRepositoryWorkflowRight"),
            array("value", "FakeClassAuthorization", "authorization"),
            array("value", "FakeClassReference", "reference")
        );
    }

    /**
     * @param Definition $definition
     * @param string     $class
     * @param string     $repository
     * @param bool       $filterType
     */
    private function assertDefinition(Definition $definition, $class, $repository, $filterType)
    {
        $this->assertSame($definition->getClass(), $repository);
        $factory = $definition->getFactory();
        $this->assertSame($factory[1], "getRepository");
        $this->assertSame($definition->getArgument(0), $class);
        if ($filterType) {
            $this->assertTrue($definition->hasMethodCall('setFilterTypeManager'));
        }
    }

    /**
     * @param string $file
     *
     * @return ContainerBuilder
     */
    private function loadContainerFromFile($file)
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.debug', false);
        $container->setParameter('kernel.cache_dir', '/tmp');
        $container->registerExtension(new OpenOrchestraWorkflowFunctionModelExtension());

        $locator = new FileLocator(__DIR__ . '/Fixtures/config/');
        $loader = new YamlFileLoader($container, $locator);
        $loader->load($file . '.yml');
        $container->getCompilerPassConfig()->setOptimizationPasses(array());
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();

        return $container;
    }
}
