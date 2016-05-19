<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH',
              realpath(dirname(__FILE__).'/../../../../../proto/cpw/application'));
set_include_path(dirname(__FILE__).'/../../../'); // include Zend


// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV',
              (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV')
                                         : 'INT'));
putenv('APPLICATION_ENV='.APPLICATION_ENV);

require_once 'Zend/Application.php';
$application = new Zend_Application(
    APPLICATION_ENV,
    array(
            'config' => array(
                    APPLICATION_PATH.'/configs/application.ini',
                    APPLICATION_PATH.'/configs/wscustomer.ini',
            ),
    )
    //APPLICATION_PATH . '/configs/application.ini'
);

Zend_Loader_Autoloader::getInstance()->registerNamespace('Psa_Dsin_');
Zend_Loader_Autoloader::getInstance()->registerNamespace('HTTP_');

define('PUBLIC_PATH', dirname(__FILE__));

/**
 *
 *
 * Pour crééer les fichiers bouchons :
 $email = 'dcr04.joepesci@yopmail.com';
 $_user = new  Psa_Dsin_GRCOnline_CustomerAt_User();
 $_user->loadUser($email);
 file_put_contents('./UserMock.'.md5($email).'.dat', serialize($_user));
 var_dump($_user);
 **/
$email = 'dcr04.joepesci@yopmail.com';
$_user = new  Psa_Dsin_GRCOnline_CustomerAt_UserMock();
$_user->MOCK_DIR_FILE = dirname(__FILE__);
$_user->loadUser($email);
var_dump($_user);

$email = 'dcr04.brucewillis@yopmail.com';
$_user = new  Psa_Dsin_GRCOnline_CustomerAt_UserMock();
$_user->MOCK_DIR_FILE = dirname(__FILE__);
$_user->loadUser($email);
var_dump($_user);
