<?php

namespace PSA\AuthenticationBundle\Authent;

use PSA\AuthenticationBundle\Authent\Service;

/**
 * PSA SA
 *
 * LICENSE
 */

/**
 * Base class for implementing connection from a XML file
 *
 * @category Psa_Dsin
 * @package Psa_Dsin_Authent
 * @copyright Copyright (c) 2013 PSA
 * @license PSA
 */
class Servicexml extends Service
{

    private $_users;

    /**
     * Function to initialize and load Xml Data File.
     * 
     * @param string $pathfile            
     */
    public function __construct ($pathfile = '')
    {
        if (file_exists($pathfile)) {
            $this->_users = simplexml_load_file($pathfile);
        }
    }

    /**
     * Function to destruction Connection to Xml data file.
     */
    public function __destruct ()
    {
        unset($this->_users);
    }

    /**
     * Function which search and return an array of User's Object if found, else an error code.
     * 
     * @param string $Uid            
     * @return array['ErrorCode'=>$CodeError, 'Data'=>$User]
     */
    public function findUserByUid ($Uid)
    {

        if (is_null($this->_users)) {
            $result['ErrorCode'] = PSA_DIRECTORY_XMLFILE_ERROR;
            
            return $result;
        }

        foreach ($this->_users->user as $user) {

            if ($user['login']->__toString() == $Uid) {

                //$result['ErrorCode'] = PSA_DIRECTORY_NO_ERROR;
                $result['Data'] = $user;
                return $result;
            }
        }

        $result['ErrorCode'] = USER_NOT_FOUND;
        
        return $result;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see Psa_Dsin_Authent_Service::getUserFirstName()
     */
    public function getUserFirstName ($pUser)
    {
        return $pUser->firstname->__toString();
    }

    /**
     * (non-PHPdoc)
     * 
     * @see Psa_Dsin_Authent_Service::getUserLastName()
     */
    public function getUserLastName ($pUser)
    {
        return $pUser->lastname->__toString();
    }

    /**
     * (non-PHPdoc)
     * 
     * @see Psa_Dsin_Authent_Service::getUserEmail()
     */
    public function getUserEmail ($pUser)
    {
        return $pUser->email->__toString();
    }

    /**
     * (non-PHPdoc)
     * 
     * @see Psa_Dsin_Authent_Service::getUserCountry()
     */
    public function getUserCountry ($pUser)
    {
        return $pUser->country->__toString();
    }

    /**
     * (non-PHPdoc)
     * 
     * @see Psa_Dsin_Authent_Service::getUserLocale()
     */
    public function getUserLocale ($pUser)
    {
        return $pUser->locale->__toString();
    }

    /**
     * (non-PHPdoc)
     * 
     * @see Psa_Dsin_Authent_Service::getUserTitle()
     */
    public function getUserTitle ($pUser)
    {
        return $pUser->title->__toString();
    }

    /**
     * (non-PHPdoc)
     * 
     * @see Psa_Dsin_Authent_Service::getUserGroups()
     */
    public function getUserGroups ($pUser)
    {
        $groups = array();
        foreach ($pUser->groups as $group) {
            $group2 = (array) $group;
            $list = (array) $group2['group'];
            $groups = (array) $list['name'];
        }
        return $groups;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see Psa_Dsin_Authent_Service::authenticateUser()
     */
    public function authenticateUser ($uid, $password)
    {
        $result['ErrorCode'] = PSA_DIRECTORY_NO_ERROR;
        $result = $this->findUserByUid($uid);
        if ($result['ErrorCode'] > PSA_DIRECTORY_NO_ERROR) {
            return $result;
        }
        $user = $result['Data'];
        if ($user['password'] != $password) {
            $result['ErrorCode'] = 521;
        }
        
        return $result;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see Psa_Dsin_Authent_Service::freeUser()
     */
    public function freeUser ($pUser)
    {
        unset($pUser);
    }
}
