<?php

use OpenOrchestra\WorkflowFunctionAdminBundle\DependencyInjection\OpenOrchestraWorkflowFunctionAdminExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;

/**
 * Class OpenOrchestraWorkflowFunctionAdminExtensionTest
 */
class OpenOrchestraWorkflowFunctionAdminExtensionTest extends AbstractBaseTestCase
{
    /**
     * @var ContainerBuilder
     */
    protected $emptyContainer;
    /**
     * @var ContainerBuilder
     */
    protected $valueContainer;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->emptyContainer = $this->loadContainerFromFile('empty');
        $this->valueContainer = $this->loadContainerFromFile('value');
    }

    /**
     * Test facades config
     *
     * @param string $parameter
     * @param string $facadeClass
     *
     * @dataProvider provideFacadesConfig
     */
    public function testFacadesConfig($parameter, $facadeClass)
    {
        $this->assertEquals('OpenOrchestra\WorkflowFunctionAdminBundle\Facade\\'.$facadeClass, $this->emptyContainer->getParameter('open_orchestra_workflow_function_admin.facade.'.$parameter.'.class'));
        $this->assertEquals('FacadeClass', $this->valueContainer->getParameter('open_orchestra_workflow_function_admin.facade.'.$parameter.'.class'));
    }

    /**
     * @return array
     */
    public function provideFacadesConfig()
    {
        return array(
            array('workflow_function', 'WorkflowFunctionFacade'),
            array('workflow_function_collection', 'WorkflowFunctionCollectionFacade'),
        );
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
        $container->setParameter('kernel.environment', 'prod');
        $container->registerExtension(new OpenOrchestraWorkflowFunctionAdminExtension());

        $locator = new FileLocator(__DIR__ . '/Fixtures/config/');
        $loader = new YamlFileLoader($container, $locator);
        $loader->load($file . '.yml');
        $container->getCompilerPassConfig()->setOptimizationPasses(array());
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();

        return $container;
    }
}
