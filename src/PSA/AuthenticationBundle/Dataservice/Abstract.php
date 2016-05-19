<?php

/**
 * This class defines all the methods to access to the Customer@ Service
 *
 * @category DataService
 * @package Customer
 * @author e379819
 */
abstract class Psa_Dsin_Dataservice_Abstract
{
    protected $_options = array();
    protected $_wsdl = array();
    /**
     * Function to initialize parameters to Conect to the Webservice
     * @param string $PathWdsl
     */
    public function __construct($PathWdsl)
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', getenv('APPLICATION_ENV'));
        $this->_wsdl = $PathWdsl;

        $this->_options = array(
            'soap_version'    =>    SOAP_1_1,
            'location'        =>    $this->getSoapLocation($config),
        );

        if ($config->proxy->sgp == 1) {
            $options2 = array(
                'proxy_host'    => $config->proxy->host,
                'proxy_port'    => $config->proxy->port,
                'proxy_login'    => $config->proxy->login,
                'proxy_password' => $config->proxy->password,
            );
            $this->_options = array_merge($this->_options, $options2);
        }
    }

    abstract protected function getSoapLocation($config);

    /**
     * Calls the webservice passed as method with an array of parameters
     *
     * @param  string        $method
     * @param  array         $params
     * @return object|string
     */
    protected function _call($method, $params)
    {
        try {
            $client = new Zend_Soap_Client($this->_wsdl, $this->_options);

            return $client->__call($method, $params);
        } catch (Exception $e) {
            return $e->getCode().' - '.$e->getMessage();
        }
    }

    protected function _callxml($method, $params)
    {
        try {
            $client = new Zend_Soap_Client($this->_wsdl, $this->_options);
            $client->__call($method, $params);

            return $client->getLastResponse();
        } catch (Exception $e) {
            return $e->getCode().' - '.$e->getMessage();
        }
    }
}
