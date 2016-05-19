<?php

namespace PSA\AuthenticationBundle\Doctrine;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManager as UserManagerFOS;
use OpenOrchestra\UserBundle\Document\User;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use FOS\UserBundle\Util\CanonicalizerInterface;

use PSA\AuthenticationBundle\Authent\Servicexml;
use PSA\AuthenticationBundle\Authent\Serviceldap;

/**
 * Class UserManager
 */
class UserManager extends UserManagerFOS
{
    protected $typeService;
    protected $filepathService;
    protected $objectManager;

    /**
     * @param EncoderFactoryInterface $encoderFactory
     * @param CanonicalizerInterface  $usernameCanonicalizer
     * @param CanonicalizerInterface  $emailCanonicalizer
     * @param string                  $type
     * @param string                  $filepath
     */
    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        CanonicalizerInterface $usernameCanonicalizer,
        CanonicalizerInterface $emailCanonicalizer,
        $type,
        $filepath
    ) {
        parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer);

        /**
         * @TODO L'instanciation du ldap devrais être ici et donc les
         * param typeService et filePathService ne devrais pas être des param de classe
         */
        $this->typeService = $type;
        $this->filepathService = $filepath;
    }

    /**
     * Deletes a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function deleteUser(UserInterface $user){}

    /**
     * Finds one user by the given criteria.
     *
     * @param array $criteria
     *
     * @return UserInterface
     */
    public function findUserBy(array $criteria)
    {
        /**
         * @TODO L'instanciation ne devrais pas être ici mais dans le constructeur
         */
        $LdapSrv = $this->getServiceType();
        if ($LdapSrv === false) {
            return null;
        }

        /**
         * @TODO Remplacer le user codé en dur par les infos recupérer en basse de données
         * ATTention il peux y avoir plusieurs $criteria['usernameCanonical'] ou $criteria['usernameCanonical']
         * Voir FOS\UserBundle\Model\UserManager pour les différentes critére
         */
/*            $result = $LdapSrv->findUserByUid($criteria);

        $userData = $result['Data'];
        $user = new User();

        $user->setUsernameCanonical($userData['login']->__toString());

        $user->setFirstName($LdapSrv->getUserFirstName($userData));
        $user->setUsername($LdapSrv->getUserLastName($userData));
        $user->setEmail($LdapSrv->getUserEmail($userData));
        $user->setLanguage($LdapSrv->getUserLocale($userData));

        $LdapSrv->freeUser($userData);*/

        $user = new User();
        $user->setUsernameCanonical('adminfr');

        $user->setFirstName('adminfr');
        $user->setUsername('adminfr');
        $user->setEmail('admin.test@adminfr.test.fr');
        $user->setLanguage('FR');

        $user->addRole('ROLE_SUPER_ADMIN');
        $user->addRole('ROLE_ADMIN');
        /**
         * @TODO Rmq possibilité d'avoir des erreurs d'accés tant que les rôles ne seront pas correctement définis
         *
         */
        return $user;

    }

    /**
     * Returns a collection with all user instances.
     *
     * @return \Traversable
     */
    public function findUsers()
    {
        // TODO: Implement findUsers() method.
    }

    /**
     * Returns the user's fully qualified class name.
     *
     * @return string
     */
    public function getClass()
    {
        return 'OpenOrchestra\UserBundle\Document\User';
    }

    /**
     * Reloads a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function reloadUser(UserInterface $user){}

    /**
     * Updates a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function updateUser(UserInterface $user, $andFlush = true)
    {
        $this->updateCanonicalFields($user);;
    }

    /**
     * Return an object of the authent service type, as it is configured in the parameters.yml
     *
     * @see Psa_Dsin_Authent_Serviceldap
     * @see Psa_Dsin_Authent_Servicexml
     * @return false, or class Psa_Dsin_Authent_Serviceldap or class Psa_Dsin_Authent_Servicexml
     */
    public function getServiceType ()
    {

        /**
         * @TODO ce code vrais être dans une factory et non ici
         */

        if (! isset($this->typeService)) {
            $this->_errorcode = self::PSA_DIRECTORY_SECTION_TYPE_NOT_FOUND;

            return false;
        }
        if (! isset($this->filepathService)) {
            $this->_errorcode = self::PSA_DIRECTORY_SECTION_FILEPATH_NOT_FOUND;

            return false;
        }
        if ($this->filepathService == '' || $this->typeService == '') {
            $this->_errorcode = self::PSA_DIRECTORY_FILEPATH_OR_TYPE_ERROR;

            return false;
        }

        switch ($this->typeService) {
            case 'xml':
                return new Servicexml($this->filepathService);
                break;
            case 'ldap':
                return new Serviceldap($this->filepathService);
                break;
            default:
                $this->_errorcode = self::PSA_DIRECTORY_TYPE_ERROR;

                return false;
                break;
        }
    }
}
