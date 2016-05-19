<?php

namespace PSA\AuthenticationBundle\Authent;

use PSA\AuthenticationBundle\Authent\Service;

/**
 * PSA SA
 *
 * LICENSE
 *
 */

/**
 * Base class for implementing connection from the PSA LDAP
 * Based on info found on the "infodev" intranet
 * This class need the "CLP" compiled library.
 * @see http://infodev.inetpsa.com/opencms/opencms/com.inetpsa.idcenter/composants/fonctionnalites/ldap/ldap_c.html
 * @category  Psa_Dsin
 * @package   Psa_Dsin_Authent
 * @copyright  Copyright (c) 2013 PSA
 * @license   PSA
 */
class Serviceldap extends Service
{
    const MAX_GROUP = 2000; // Maxmimum number of group to scan in the LDAP.
    /**
    * Function to initialize Connection to Ldap Dll.
    * @param string $pathfile
    * @fail if the CL compiled library is missing
    */
    public function __construct($pathfile = '')
    {
        if (!function_exists('LDAPC_NEW')) {
            die('Function LDAPC_NEW() not found. The compiled library "Composant LDAP C" may not be available on your PHP server.');
        }
        LDAPC_NEW($pathfile);
    }

    /**
     *  Function to destruction Connection to Ldap Dll.
     */
    public function __destruct()
    {
        LDAPC_END();
    }

    /**
     * Function which search and return an array of User's Object if found, else an error code.
     * @param  string                         $Uid
     * @return array['ErrorCode'=>$CodeError, 'Data'=>$User]
     */
    private function findUserByUid($Uid)
    {
        $result = array();
        $result['ErrorCode'] = PSA_DIRECTORY_NO_ERROR;
        $pUser = findLdapUserByUid($Uid);
        if (isObjectNotNull($pUser)) {
            $result['ErrorCode'] = PSA_DIRECTORY_NO_ERROR;
            $result['Data'] = $pUser;
        } else {
            $result['ErrorCode'] = getLdapErrorCode();
        }

        return $result;
    }

    /**
     * (non-PHPdoc)
     * @see Psa_Dsin_Authent_Service::getUserFirstName()
     */
    public function getUserFirstName($pUser)
    {
        return getLdapUserFirstName($pUser);
    }

    /**
     * (non-PHPdoc)
     * @see Psa_Dsin_Authent_Service::getUserLastName()
     */
    public function getUserLastName($pUser)
    {
        return getLdapUserLastName($pUser);
    }

    /**
     * (non-PHPdoc)
     * @see Psa_Dsin_Authent_Service::getUserEmail()
     */
    public function getUserEmail($pUser)
    {
        return getLdapUserMail($pUser);
    }

    /**
     * (non-PHPdoc)
     * @see Psa_Dsin_Authent_Service::getUserCountry()
     */
    public function getUserCountry($pUser)
    {
        return getLdapUserPays($pUser);
    }

    /**
     * (non-PHPdoc)
     * @see Psa_Dsin_Authent_Service::getUserLocale()
     */
    public function getUserLocale($pUser)
    {
        return getLdapUserLocale($pUser);
    }

    /**
     * (non-PHPdoc)
     * @see Psa_Dsin_Authent_Service::getUserTitle()
     */
    public function getUserTitle($pUser)
    {
        return getLdapUserPersonalTitle($pUser);
    }

    /**
     * (non-PHPdoc)
     * @see Psa_Dsin_Authent_Service::getUserGroups()
     */
    public function getUserGroups($pUser)
    {
        $groups    = array();
        $listGroups = findLdapUserGroups($pUser);
        $i            = 0;
        if (isObjectNotNull($listGroups)) {
            while (isObjectNotNull($pGroup = getListGroupElement($listGroups, $i)) && $i < self::MAX_GROUP) {
                $grpName = getLdapGroupName($pGroup);
                if ($grpName) {
                    $isMemberOf = isLdapUserMemberOfGroupName($pUser, getLdapGroupName($pGroup));
                    if ($isMemberOf) {
                        $groups[] = $grpName;
                    }
                }
                $i++;
            }
            if ($i >= self::MAX_GROUP) {
                die('ServiceLdap failed : findLdapUserGroups return more than MAX_GROUP values');
            }
        }
        freeLdapPtrListGroups($listGroups);

        return $groups;
    }

    /**
     * (non-PHPdoc)
     * @see Psa_Dsin_Authent_Service::authenticateUser()
     */
    public function authenticateUser($uid, $password)
    {
        $result = $this->findUserByUid($uid);
        if ($result['ErrorCode']>0) {
            return $result;
        }
        if (!authenticateLdapUser($result['Data'], $password)) {
            $result['ErrorCode'] = getLdapErrorCode();
        }

        return $result;
    }

    /**
     * (non-PHPdoc)
     * @see Psa_Dsin_Authent_Service::freeUser()
     */
    public function freeUser($pUser)
    {
        if (isset($pUser)) {
            freeLdapPtrUser($pUser);
        }
    }
}
