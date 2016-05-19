<?php

/**
 * This class defines all the methods to access to the Customer@ Service
 *
 * @category  Psa_Dsin
 * @package   Psa_Dsin_GRCOnline
 * @copyright  Copyright (c) 2013 PSA
 * @license   PSA
 */
abstract class Psa_Dsin_GRCOnline_Abstract
{
    protected $_options = array();
    protected $_wsdl = array();
    public $_config = array();

    /**
     * Function to initialize parameters to Connect to the Webservice
     * @param string $PathWdsl
     */
    public function __construct($PathWdsl, $customConfig = null)
    {
        $this->_wsdl = $PathWdsl;
        //$this->_config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/wscustomer.ini', getenv('APPLICATION_ENV'));
        // $this->_config = new Zend_Config(require  Pelican::$config['DOCUMENT_INIT'] . "/application/configs/local/wscustomer.".$_ENV["TYPE_ENVIRONNEMENT"].".ini.php");

        // Loading default configuration file, and merging with custom config to override specified parameters
        $config = require Pelican::$config['DOCUMENT_INIT']."/application/configs/local/wscustomer.".$_ENV["TYPE_ENVIRONNEMENT"].".ini.php";
        if (!empty($customConfig)) {
            $config = array_replace_recursive($config, $customConfig);
        }
        $this->_config = new Zend_Config($config);

        $this->_options = array(
            'soap_version'    =>    SOAP_1_1,
            'cache_wsdl'    =>    WSDL_CACHE_NONE,
            'location'        =>    $this->getSoapLocation($this->_config),
        );
        $this->_options = array_merge($this->_options, $this->getProxy());
    }

    /**
     * Return the information Proxy from the configuration ini file.
     * @return array('proxy_host'=> 'value');
     */
    public function getProxy()
    {
        $proxy = array();
        if ($this->_config->proxy->sgp == 1) {
            $proxy = array(
                'proxy_host'    => $this->_config->proxy->host,
                'proxy_port'    => $this->_config->proxy->port,
                'proxy_login'    => $this->_config->proxy->login,
                'proxy_password' => $this->_config->proxy->password,
            );
        }

        return $proxy;
    }

    /**
     * Return the information Proxy from the configuration ini file.
     * @return array('proxy_host'=> 'value');
     */
    public function getOauthProxy()
    {
        $proxy = array();
        if ($this->_config->proxy->sgp == 1) {
            $proxy = array(
                'proxy_host'    => $this->_config->proxy->host,
                'proxy_port'    => $this->_config->proxy->port,
                'proxy_user'    => $this->_config->proxy->login,
                'proxy_password' => $this->_config->proxy->password,
            );
        }

        return $proxy;
    }

    /**
     * Return a string url of the Customer Webservice
     * @return string
     */
    abstract protected function getSoapLocation();

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

    /**
     * Calls the webservice passed as method with an array of parameters and return the Xml string.
     *
     * @param  string $method
     * @param  array  $params
     * @return string
     */
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
