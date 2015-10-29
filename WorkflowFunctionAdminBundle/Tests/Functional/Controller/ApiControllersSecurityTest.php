<?php

namespace OpenOrchestra\WorkflowFunctionAdminBundle\Tests\Functional\Controller;

use OpenOrchestra\ApiBundle\Tests\Functional\Controller\AbstractControllerTest;

/**
 * Class ApiControllersSecurityTest
 */
class ApiControllersSecurityTest extends AbstractControllerTest
{
    protected $username = "userNoAccess";
    protected $password = "userNoAccess";

    /**
     * @param string $url
     * @param string $method
     *
     * @dataProvider provideApiUrl
     */
    public function testApi($url, $method = 'GET')
    {
        $this->client->request($method, $url);

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function provideApiUrl()
    {
        return array(
            array('/api/workflow-function/root'),
            array('/api/workflow-function'),
            array('/api/workflow-function/root/delete', 'DELETE'),

        );
    }
}
