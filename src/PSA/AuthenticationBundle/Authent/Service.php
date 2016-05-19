<?php

namespace PSA\AuthenticationBundle\Authent;

/**
 * PSA SA
 *
 * LICENSE
 *
 */

/**
 * Absrtact class for manage authent and connexion to the PHP Factory back office.
 * Must be implemented in a Psa_Dsin_Authent_Serviceldap or Psa_Dsin_Authent_Servicexml
 *
 * Country code returned in this class are the same as the ISO-3166-2 codes, except
 * for the "CT" code that indicates a virtual "Central / headquarter" country.
 *
 * @see Psa_Dsin_Authent_Serviceldap
 * @see Psa_Dsin_Authent_Servicexml
 * @see http://fr.wikipedia.org/wiki/ISO_3166-2
 *
 * @category  Psa_Dsin
 * @package   Psa_Dsin_Authent
 * @copyright  Copyright (c) 2013 PSA
 * @license   PSA
 */
abstract class Service
{
    const MAX_GROUP = 2000;

    const PSA_DIRECTORY_NO_ERROR = 0; // no error
    const PSA_DIRECTORY_SECTION_NOT_FOUND = 1000; // application.ini miss the config section
    const PSA_DIRECTORY_SECTION_TYPE_NOT_FOUND = 1001; // application.ini miss the directory.type section
    const PSA_DIRECTORY_SECTION_FILEPATH_NOT_FOUND = 1002; // application.ini miss the directory.filepath section
    const PSA_DIRECTORY_TYPE_ERROR = 1003; // application.ini directory.type section invalid
    const PSA_DIRECTORY_FILEPATH_OR_TYPE_ERROR = 1004; // application.ini directory.filepath section invalid
    const PSA_DIRECTORY_XMLFILE_ERROR = 1005; // mock xml file is not well formed

    /**
     * Convert an error code to a constant name
     * @param  integer $errCode error code return by getLdapErrorCode
     * @return string  ('LDAP_NO_ERROR', 'LDAP_PSA_UNKNOWN_ERROR_CODE' or an other const name defined in the CLP library
     */
    public static function errorCodeToConstName($errCode)
    {
        global $LDAP_PSA_ALL_CONSTS;
        if (!isset($LDAP_PSA_ALL_CONSTS)) {
            $LDAP_PSA_ALL_CONSTS[0] = 'LDAP_NO_ERROR';
            $tmp                    = get_defined_constants(true);
            if (isset($tmp['ldapc'])) {
                $tmp = $tmp['ldapc'];
                foreach ($tmp as $constname => $constvalue) {
                    if ($constvalue > 255) {
                        $LDAP_PSA_ALL_CONSTS[$constvalue] = $constname;
                    }
                }
            }
            $LDAP_PSA_ALL_CONSTS[PSA_DIRECTORY_SECTION_NOT_FOUND]            = 'PSA_DIRECTORY_SECTION_NOT_FOUND';
            $LDAP_PSA_ALL_CONSTS[PSA_DIRECTORY_SECTION_TYPE_NOT_FOUND]        = 'PSA_DIRECTORY_SECTION_TYPE_NOT_FOUND';
            $LDAP_PSA_ALL_CONSTS[PSA_DIRECTORY_SECTION_FILEPATH_NOT_FOUND]    = 'PSA_DIRECTORY_SECTION_FILEPATH_NOT_FOUND';
            $LDAP_PSA_ALL_CONSTS[PSA_DIRECTORY_TYPE_ERROR]                    = 'PSA_DIRECTORY_TYPE_ERROR';
            $LDAP_PSA_ALL_CONSTS[PSA_DIRECTORY_FILEPATH_OR_TYPE_ERROR]        = 'PSA_DIRECTORY_FILEPATH_OR_TYPE_ERROR';
            $LDAP_PSA_ALL_CONSTS[PSA_DIRECTORY_XMLFILE_ERROR]                = 'PSA_DIRECTORY_XMLFILE_ERROR';
        }

        if (!isset($LDAP_PSA_ALL_CONSTS[$errCode])) {
            return 'LDAP_PSA_UNKNOWN_ERROR_CODE';
        } else {
            return $LDAP_PSA_ALL_CONSTS[$errCode];
        }
    }

    /**
     * @return string,    Firstname of user'object, Format String, Example : "Dupont"
     * @param  UserObject $pUser
     */
    abstract public function getUserFirstName($pUser);

    /**
     * @return string,    LastName of user's object, Format String, Example : "John"
     * @param  UserObject $pUser
     */
    abstract public function getUserLastName($pUser);

    /**
     * @return string,    the Email of the User, Format String, Example : "user@domaine.com"
     * @param  UserObject $pUser
     */
    abstract public function getUserEmail($pUser);

    /**
     * @return string,    the Country of the User, Format code ISO 2,Example : "FR"
     * @param  UserObject $pUser
     */
    abstract public function getUserCountry($pUser);

    /**
     * @return string,    the Locale of the User, Format code ISO 2, Example : "FR"
     * @param  UserObject $pUser
     */
    abstract public function getUserLocale($pUser);

    /**
     * @return string,    the Title of the User, Example : "Mr"
     * @param  UserObject $pUser
     */
    abstract public function getUserTitle($pUser);

    /**
     * return list of the user's rigths
     * @return array('CPW.WEBMASTER.FR','CPW.CONTRIBUTEUR.ES');
     * @param  UserObject                                       $pUser
     */
    abstract public function getUserGroups($pUser);

    /**
     * Check in Ldap Directory the user information UID and Password.
     * @return array('ErrorCode'=>CodeNumber, 'Data' => UserObject);
     * @param  string                         $uid
     * @param  string                         $password
     */
    abstract public function authenticateUser($uid, $password);

    /**
     * free ressources of the object
     * @param UserObject $pUser
     */
    abstract public function freeUser($pUser);
}
