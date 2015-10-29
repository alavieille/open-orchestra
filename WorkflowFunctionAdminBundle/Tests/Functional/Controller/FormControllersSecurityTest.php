<?php

namespace OpenOrchestra\WorkflowFunctionAdminBundle\Tests\Functional\Controller;

use OpenOrchestra\BackofficeBundle\Tests\Functional\Controller\AbstractControllerTest;

/**
 * Class FormControllersSecurityTest
 */
class FormControllersSecurityTest extends AbstractControllerTest
{
    protected $username = 'userNoAccess';
    protected $password = 'userNoAccess';

    /**
     * @param string $url
     *
     * @dataProvider provideApiUrl
     */
    public function testForm($url)
    {
        $this->client->request('GET', $url);
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function provideApiUrl()
    {
        return array(
            array('/admin/workflow-function/new'),
            array('/admin/workflow-function/form/root'),
        );
    }
}
