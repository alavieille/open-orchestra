<?php
/**
 * PSA SA
 *
 * LICENSE
 *
 */

/**
 * class for manage log application.
 *
 * @category  Psa_Dsin
 * @package   Psa_Dsin_Log
 * @copyright  Copyright (c) 2013 PSA
 * @license   PSA
 */
class Psa_Dsin_Log_Log
{
    protected static $_log;

    public static function getInstance()
    {
        if (null === self::$_log) {
            try {
                $frontController = Zend_Controller_Front::getInstance();
                $LogParam  = Pelican:: $config['LOG_LDAP'];
                self::$_log = new Zend_Log(new Zend_Log_Writer_Stream($LogParam['filepath']));
            } catch (Exception $e) {
                echo 'Exception reçue : ',  $e->getMessage(), "\n";
            }
        }

        return self::$_log;
    }

    public static function log($message, $priority = Zend_Log::INFO)
    {
        self::getInstance()->log($message, $priority);
    }
}
