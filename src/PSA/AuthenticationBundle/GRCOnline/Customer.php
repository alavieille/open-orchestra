<?php

/**
 * This class defines all the methods to access to the Customer@ Service
 *
 * @category  Psa_Dsin
 * @package   Psa_Dsin_GRCOnline
 * @copyright  Copyright (c) 2013 PSA
 * @license   PSA
 */
class Psa_Dsin_GRCOnline_Customer extends Psa_Dsin_GRCOnline_Abstract
{
    protected $_sitecode;

    public function __construct($PathWdsl, $customConfig = null)
    {
        parent::__construct($PathWdsl, $customConfig);
        $this->_sitecode = $this->_config->sitecode;
    }

    /**
     * Return a string url of the Customer Webservice
     * @return string
     */
    protected function getSoapLocation()
    {
        return $this->_config->dcr->bend->crmservice;
    }

    /**
     * getElement method
     *
     * @param  string $ticket
     * @param  string $culture
     * @param  string $datatype (xml ou empty)
     * @return object return the object response or an error message
     */
    public function getElement($ticket, $culture, $datatype = '')
    {
        $params = array(
            'parameters' => array(
                'GetElementRequest' => array(
                    'Ticket'    => $ticket,
                    'culture'    => $culture,
                ),
            ),
        );
        if ($datatype == 'xml') {
            return $this->_callxml('GetElement', $params);
        } else {
            return $this->_call('GetElement', $params);
        }
    }

    /**
     * getTicket method
     *
     * @param  string $email
     * @param  string $action
     * @param  string $duration
     * @return object return the object response or an error message
     */
    public function getTicket($KeyFieldValue, $action, $duration, $isEmail = true)
    {
        $KeyFieldName = $isEmail ? 'Email' : 'CustomerOID';
        $params = array(
                'parameters' => array(
                        'GetTicketRequest' => array(
                            'SiteCode'    => $this->_sitecode,
                            $KeyFieldName    => $KeyFieldValue,
                            'Action'    => $action,
                            'Duration'    => $duration,
                        ),
                ),
        );

        return $this->_call('GetTicket', $params);
    }

    /**
     * GetAuthenticatedTicket method
     *
     * @param  string $Email
     * @param  string $Password
     * @param  string $Action
     * @param  string $Duration
     * @param  string $Remember
     * @return object return the object response or an error message
     */
    public function GetAuthenticatedTicket($Email, $Password, $Action, $Duration, $Remember)
    {
        $params = array(
            'parameters' => array(
                'GetAuthenticatedTicketRequest' => array(
                    'Email'        => $Email,
                    'Password'        => $Password,
                    'SiteCode'        => $this->_sitecode,
                    'Action'        => $Action,
                    'Duration'        => $Duration,
                    'Remember'        => $Remember,
                    ),
            ),
        );

        return $this->_call('GetAuthenticatedTicket', $params);
    }

    /**
     * CreateAccount method
     *
     * @param  array  $accountProperties
     * @return object return the object response or an error message
     */
    public function CreateAccount($accountProperties)
    {
        $params = array(
                'parameters' => array(
                    'CreateAccountRequest' => array(
                        'AccountProperties' => $accountProperties,
                        'SiteCode' => $this->_sitecode,
                    ),
                ),
        );

        return $this->_call('CreateAccount', $params);
    }

    /**
     * CheckAccountStatus method
     *
     * @param  string $email
     * @return object return the object response or an error message
     */
    public function CheckAccountStatus($email)
    {
        $params = array(
                'parameters' => array(
                    'CheckAccountStatusRequest' => array(
                        'email' => $email,
                        'siteCode' => $this->_sitecode,
                    ),
                ),
        );

        return $this->_call('CheckAccountStatus', $params);
    }

    /**
     * ActivatesAccount method
     *
     * @param  string $ticket
     * @return object return the object response or an error message
     */
    public function ActivatesAccount($ticket)
    {
        $params = array(
                'parameters' => array(
                        'ActivatesAccountRequest' => array(
                            'Ticket' => $ticket,
                            'SiteCode' => $this->_sitecode,
                        ),
                ),
        );

        return $this->_call('ActivatesAccount', $params);
    }

    /**
     * CheckToken method
     *
     * @param  string $ticket
     * @return object return the object response or an error message
     */
    public function CheckToken($ticket)
    {
        $params = array(
                'parameters' => array(
                        'CheckTokenRequest' => array(
                            'Ticket' => $ticket,
                            'siteCode' => $this->_sitecode,
                        ),
                ),
        );

        return $this->_call('CheckToken', $params);
    }

    /**
     * GetAccount method
     *
     * @param  string $ticket
     * @return object return the object response or an error message
     */
    public function GetAccount($ticket)
    {
        $params = array(
            'parameters' => array(
                'GetAccountRequest' => array(
                    'AuthenticatedTicket' => $ticket,
                    'SiteCode' => $this->_sitecode,
                ),
            ),
        );

        return $this->_call('GetAccount', $params);
    }

    /**
     * SetPassword method
     *
     * @param  string $password
     * @param  string $ticket
     * @return object return the object response or an error message
     */
    public function SetPassword($password, $ticket)
    {
        $params = array(
            'parameters' => array(
                    'SetPasswordRequest' => array(
                        'Password' => $password,
                        'Ticket' => $ticket,
                        'SiteCode' => $this->_sitecode,
                    ),
            ),
        );

        return $this->_call('SetPassword', $params);
    }

    /**
     * Subscribe method
     *
     * @param  string $ticket
     * @return object return the object response or an error message
     */
    public function Subscribe($ticket)
    {
        $params = array(
                'parameters' => array(
                        'SuscribeRequest' => array(
                                'AuthenticatedTicket' => $ticket,
                                'SiteCode' => $this->_sitecode,
                        ),
                ),
        );

        return $this->_call('Subscribe', $params);
    }

    /**
     * Unsubscribe method
     *
     * @param  string $AuthenticatedTicket
     * @return object return the object response or an error message
     */
    public function Unsubscribe($AuthenticatedTicket)
    {
        $params = array(
            'parameters' => array(
                'UnsubscribeRequest' => array(
                    'AuthenticatedTicket' => $AuthenticatedTicket,
                    'SiteCode' => $this->_sitecode,
                ),
            ),
        );

        return $this->_call('Unsubscribe', $params);
    }

    /**
     * UpdateAccount method
     *
     * @param  array  $accountProperties
     * @param  array  $AuthenticatedTicket
     * @return object return the object response or an error message
     */
    public function UpdateAccount($accountProperties, $AuthenticatedTicket)
    {
        $params = array(
            'parameters' => array(
                'UpdateAccountRequest' => array(
                    'AuthenticatedTicket' => $AuthenticatedTicket,
                    'AccountProperties' => $accountProperties,
                    'SiteCode' => $this->_sitecode,
                ),
            ),
        );

        return $this->_call('UpdateAccount', $params);
    }

    /**
     * CheckSiteSubscriptions method
     *
     * @param  array  $accountProperties
     * @param  array  $AuthenticatedTicket
     * @return object return the object response or an error message
     */
    public function CheckSiteSubscriptions($Ticket, $Culture)
    {
        $params = array(
            'parameters' => array(
                'CheckSiteSubscriptionsRequest' => array(
                    'Ticket' => $Ticket,
                    'Culture' => $Culture,
                ),
            ),
        );

        return $this->_call('CheckSiteSubscriptions', $params);
    }

    public function PushElement($instance, $ticket = false)
    {
        $params = array(
            'parameters' => array(
                'PushElementRequest' => array(
                    'instance' => $instance,
                    'Ticket' => $ticket,
                ),
            ),
        );

        return $this->_call('PushElement', $params);
    }

    public function UpdateRelationShip($instance, $ticket)
    {
        $params = array(
                'parameters' => array(
                        'UpdateRelationshipRequest' => array(
                                'instance' => $instance,
                                'Ticket' => $ticket,
                                'culture' => 'fr-FR',
                        ),
                ),
        );

        return $this->_call('UpdateRelationShip', $params);
    }

    public function SubscribeNewsletter($NewsletterCode, $ticket, $activesubscription = 0)
    {
        $params = array(
            'parameters' => array(
                'SubscribeNewsletterRequest' => array(
                    'Ticket' => $ticket,
                    'NewsletterCode' => $NewsletterCode,
                    'SiteCode' => $this->_sitecode,
                    'StatusAccount' => $activesubscription,
                ),
            ),
        );

        return $this->_call('SubscribeNewsletter', $params);
    }

    public function UnsubscribeNewsletter($NewsletterCode, $ticket)
    {
        $params = array(
                'parameters' => array(
                        'UnsubscribeNewsletterRequest' => array(
                                'Ticket' => $ticket,
                                'NewsletterCode' => $NewsletterCode,
                                'SiteCode' => $this->_sitecode,
                        ),
                ),
        );

        return $this->_call('UnsubscribeNewsletter', $params);
    }
}
