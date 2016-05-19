<?php

/**
 * This class defines all the methods to access to the DataService layer
 *
 * @category DataService
 * @package Internaute
 * @author e379819
 */
class Psa_Dsin_Dataservice_Internaute extends Psa_Dsin_Dataservice_Abstract
{

    protected function getSoapLocation($config)
    {
        return $config->dataservice->internaute->location;
    }

    /**
     * ActivatesAccount method
     *
     * @param  string $ticket
     * @param  string $siteCode
     * @return object return the object response or an error message
     */
    public function ActivatesAccount($ticket, $siteCode)
    {
        $params = array(
            'parameters' => array(
                'ActivatesAccount' => array(
                    'activatesAccountType' => array(
                        'Ticket'    => $ticket,
                        'SiteCode'    => $siteCode,
                    ),
                ),
            ),
        );

        return $this->_call('ActivatesAccount', $params);
    }

    /**
     * CheckAccountStatus method
     *
     * @param  string $email
     * @param  string $siteCode
     * @return object return the object response or an error message
     */
    public function CheckAccountStatus($email, $siteCode)
    {
        $params = array(
            'parameters' => array(
                'CheckAccountStatus' => array(
                    'checkStatusTypeParameters' => array(
                        'email'    => $email,
                        'siteCode'    => $siteCode,
                    ),
                ),
            ),
        );

        return $this->_call('CheckAccountStatus', $params);
    }

    /**
     * CreateAccount method
     *
     * @param  AccountProperties $accountProperties
     * @param  string            $siteCode
     * @return object            return the object response or an error message
     */
    public function CreateAccount($accountProperties, $siteCode)
    {
        $params = array(
            'parameters' => array(
                'CreateAccount' => array(
                    'createAccountType' => array(
                        'AccountProperties' => $accountProperties,
                        'SiteCode' => $siteCode,
                    ),
                ),
            ),
        );

        return $this->_call('CreateAccount', $params);
    }

    /**
     * GetAccount method
     *
     * @param  string $authenticatedTicket
     * @param  string $siteCode
     * @return object return the object response or an error message
     */
    public function GetAccount($authenticatedTicket, $siteCode)
    {
        $params = array(
            'parameters' => array(
                'GetAccount' => array(
                    'getAccountType' => array(
                        'AuthenticatedTicket' => $authenticatedTicket,
                        'SiteCode' => $siteCode,
                    ),
                ),
            ),
        );

        return $this->_call('GetAccount', $params);
    }

    /**
     * GetAuthenticatedTicket method
     *
     * @param  string $email
     * @param  string $password
     * @param  string $siteCode
     * @param  string $action
     * @param  string $remember
     * @return string
     */
    public function GetAuthenticatedTicket($email, $password, $siteCode, $action, $duration, $remember = '0')
    {
        $params = array(
            'parameters' => array(
                'GetAuthenticatedTicket' => array(
                    'authTicketParameters' => array(
                        'Email' => $email,
                        'Password' => $password,
                        'SiteCode' => $siteCode,
                        'Action' => $action,
                        'Duration' => $duration,
                        'Remember' => $remember,
                    ),
                ),
            ),
        );

        return $this->_call('GetAuthenticatedTicket', $params);
    }

    /**
     * GetReferential method
     *
     * @param  string $referentialType
     * @param  string $culture
     * @param  string $siteCode
     * @return object return the object response or an error message
     */
    public function GetReferential($referentialType, $culture, $siteCode)
    {
        $params = array(
            'parameters' => array(
                'GetReferential' => array(
                    'getReferentialType' => array(
                        'ReferentialType'    => $referentialType,
                        'Culture'            => $culture,
                        'SiteCode'            => $siteCode,
                    ),
                ),
            ),
        );

        return $this->_call('GetReferential', $params);
    }

    /**
     * GetTicket method
     *
     * @param  string $email
     * @param  string $siteCode
     * @param  string $action
     * @param  int    $duration
     * @return object return the object response or an error message
     */
    public function GetTicket($email, $siteCode, $action, $duration)
    {
        $params = array(
            'parameters' => array(
                'GetTicket' => array(
                    'getTicket' => array(
                        'SiteCode'    => $siteCode,
                        'Email'        => $email,
                        'Action'    => $action,
                        'Duration'    => $duration,
                    ),
                ),
            ),
        );

        return $this->_call('GetTicket', $params);
    }

    /**
     * SetPassWord method
     *
     * @param  string $password
     * @param  string $ticket
     * @param  string $siteCode
     * @return object return the object response or an error message
     */
    public function SetPassword($password, $ticket, $siteCode)
    {
        $params = array(
            'parameters' => array(
                'SetPassword' => array(
                    'setPasswordType' => array(
                        'Password' => $password,
                        'Ticket' => $ticket,
                        'SiteCode' => $siteCode,
                    ),
                ),
            ),
        );

        return $this->_call('SetPassword', $params);
    }

    /**
     * Subscribe method
     *
     * @param  string $authenticatedTicket
     * @param  string $siteCode
     * @return object return the object response or an error message
     */
    public function Subscribe($authenticatedTicket, $siteCode)
    {
        $params = array(
            'parameters' => array(
                'Subscribe' => array(
                    'suscribeType' => array(
                        'AuthenticatedTicket' => $authenticatedTicket,
                        'SiteCode' => $siteCode,
                    ),
                ),
            ),
        );

        return $this->_call('Subscribe', $params);
    }

    /**
     * SubscribeNewsletter method
     *
     * @param  string   $ticket
     * @param  string   $siteCode
     * @param  string   $newsletterCode
     * @param  dateTime $dateActivation
     * @param  bool     $statusAccount
     * @return object   return the object response or an error message
     */
    public function SubscribeNewsletter($ticket, $siteCode, $newsletterCode, $dateActivation, $statusAccount)
    {
        $params = array(
            'parameters' => array(
                'SubscribeNewsletter' => array(
                    'subscribeNewsletterType' => array(
                        'Ticket'        => $ticket,
                        'SiteCode'        => $siteCode,
                        'NewsletterCode' => $newsletterCode,
                        'DateActivation' => $dateActivation,
                        'StatusAccount' => $statusAccount,
                    ),
                ),
            ),
        );

        return $this->_call('SubscribeNewsletter', $params);
    }

    /**
     * UnSubscribe method
     *
     * @param  string $authenticatedTicket
     * @param  string $siteCode
     * @return object return the object response or an error message
     */
    public function Unsubscribe($authenticatedTicket, $siteCode)
    {
        $params = array(
            'parameters' => array(
                'Unsubscribe' => array(
                    'unsuscribeType' => array(
                        'AuthenticatedTicket'    => $authenticatedTicket,
                        'SiteCode'                => $siteCode,
                    ),
                ),
            ),
        );

        return $this->_call('Unsubscribe', $params);
    }

    /**
     * UnsubscribeNewsletter method
     *
     * @param  string $ticket
     * @param  string $siteCode
     * @param  string $newsletterCode
     * @return object return the object response or an error message
     */
    public function UnsubscribeNewsletter($ticket, $siteCode, $newsletterCode)
    {
        $params = array(
            'parameters' => array(
                'UnsubscribeNewsletter' => array(
                    'unsubscribeNewsletterType' => array(
                        'Ticket'        => $ticket,
                        'SiteCode'        => $siteCode,
                        'NewsletterCode' => $newsletterCode,
                    ),
                ),
            ),
        );

        return $this->_call('UnsubscribeNewsletter', $params);
    }

    /**
     * UpdateAccount method
     *
     * @param  string            $authenticatedTicket
     * @param  AccountProperties $accountProperties
     * @param  string            $siteCode
     * @return object            return the object response or an error message
     */
    public function UpdateAccount($authenticatedTicket, $accountProperties, $siteCode)
    {
        $params = array(
            'parameters' => array(
                'UpdateAccount' => array(
                    'updateAccountType' => array(
                        'AuthenticatedTicket'    => $authenticatedTicket,
                        'AccountProperties'    => $accountProperties,
                        'SiteCode'                => $siteCode,
                    ),
                ),
            ),
        );

        return $this->_call('UpdateAccount', $params);
    }

    /**
     * ForeignCreateAccount method
     *
     * @param  array  $accountProperties
     * @param  string $siteCode
     * @return object
     */
    public function ForeignCreateAccount($accountProperties, $siteCode)
    {
        $params = array(
            'parameters' => array(
                'ForeignCreateAccount' => array(
                    'foreignCreateAccountType' => array(
                        'AccountProperties' => $accountProperties,
                        'SiteCode' => $siteCode,
                    ),
                ),
            ),
        );

        return $this->_call('ForeignCreateAccount', $params);
    }
}
