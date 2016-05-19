<?php

/**
 * This class manage all methods of the Customer@ Service
 *
 * @category  Psa_Dsin
 * @package   Psa_Dsin_GRCOnline
 * @copyright  Copyright (c) 2013 PSA
 * @license   PSA
 */

// list of the Customer@ Service Return Code
define('BDI_RETURN_OK',            "OK");                        // No error.
define('BDI_RETURN_BAD',            "BAD");                    // One or more fields had values formatted wrong or incorrect.
define('BDI_RETURN_ERROR',            "ERROR");                    // An error occurs in the WS process
define('BDI_RETURN_EXPIRED',        "EXPIRED");                    // The ticket passed as parameter has expired
define('BDI_RETURN_NOT_AUTH',        "NOT_AUTHORIZED");            // Calling this method is not allowed
define('BDI_RETURN_AUTH_FAILED',    "AUTHENTICATION_FAILED");    // The authentification has failed
define('BDI_RETURN_SITE_NF',        "SITE_CODE_NOT_FOUND");        // The sitecode passed as parameter does not exist

// list of the Customer@ Service User Status Code
define('BDI_STATUS_NA',            "NA");                        // Not Appropriated
define('BDI_STATUS_KN',            "KNOWN");                    // the user is known but is inactive and without subscription
define('BDI_STATUS_UN',                "UNKNOWN");                    // the user is unknown
define('BDI_STATUS_SS',                "SIGNED_SUBSCRIBER");        // the user is known and the subscriber requesting site
define('BDI_STATUS_NTS',            "NOT_SUBSCRIBER");            // the user is known but not the subscriber requesting site
define('BDI_STATUS_NOS',            "NO_SUBSCRIPTIONS");        // the user is known but has no subscriptions
define('BDI_STATUS_NTA',            "NOT_ACTIVATED");            // the user is known but his account has not been activated
define('BDI_STATUS_US',                "UNSIGNED_SUBSCRIBER");        // the user has no password with a newsletter subscription
define('BDI_STATUS_NC',            "NEED_CONFIRMATION");        // the user has no password with a waiting newsletter subscription
define('BDI_STATUS_NI',                "NOT_INITIALIZED");            // the user is known, created by a process of affiliation. Waiting for a password and an activation key

class Psa_Dsin_GRCOnline_Customermanager
{
  protected $_mng;
    protected $_onError = false;
    protected $_code;
    protected $_data;

  /*
   * Contructor which initalize the Customer@Service
   *
   */
  public function __construct($PathWdsl, $customConfig = null)
  {
      $this->_mng = new Psa_Dsin_GRCOnline_Customer($PathWdsl, $customConfig);
  }

  /**
   * Return the information Proxy from the configuration ini file.
   * @return array('proxy_host'=> 'value');
   */
  public function getProxy()
  {
      return $this->_mng->getProxy();
  }

  /**
   * Return the information Oauth Proxy from the configuration ini file.
   * @return array('proxy_host'=> 'value');
   */
  public function getOauthProxy()
  {
      return $this->_mng->getOauthProxy();
  }

  /**
   * Return the information OpenId from the configuration ini file.
   * @return array('identity'=> 'value');
   */
  public function getOpenId()
  {
      $OpenId = array(
        'identity'    => $this->_mng->_config->openid->identity,
        'realm'    => $this->_mng->_config->openid->realm,
        'returnUrl' => $this->_mng->_config->openid->returnUrl,
    );

      return $OpenId;
  }

  /**
   * Return the information oauth from the configuration ini file.
   * @return array('urlrequesttoken'=> 'value');
   */
  public function getOauth()
  {
      $OpenId = array(
        'urlrequesttoken'    => $this->_mng->_config->oauth->urlrequesttoken,
        'urlauthorize'        => $this->_mng->_config->oauth->urlauthorize,
        'urlaccesstoken'    => $this->_mng->_config->oauth->urlaccesstoken,
        'urlcallback'        => $this->_mng->_config->oauth->urlcallback,
        'consumerkey'        => $this->_mng->_config->oauth->consumerkey,
        'consumersecret'    => $this->_mng->_config->oauth->consumersecret,
        'method'            => $this->_mng->_config->oauth->method,
    );

      return $OpenId;
  }

  /**
   * Check last Webservice methode call result.
   * return TRUE if the webservice method has failed, True if success
   * @return bool
   */
  public function onError()
  {
      return $this->_onError;
  }

  /**
   * Return the data of the last Webservice Method Call.
   * @return string|object
   */
  public function data()
  {
      return $this->_data;
  }

  /**
   * Return the last Statut code of the webservice method
   * @return string|object
   */
  public function code()
  {
      return $this->_code;
  }

  /**
   * GetAccount method
   * Get All informations of an account, by passing a ticket
   * @param string $ticket
   * @return object return the object response or an error message
   */
  public function GetAccount($ticket)
  {
      $data = $this->_mng->GetAccount($ticket);
      $this->_code = $data->GetAccountResponse->ReturnCode;
      $this->_onError = ($this->_code != BDI_RETURN_OK);
      if (!$this->_onError) {
          foreach ($data->GetAccountResponse->AccountProperties as $Properties) {
              foreach ($Properties as $Propertie) {
                  $account[$Propertie->PropertyCode] = $Propertie->PropertyValue;
              }
          }
          $this->_data = $account;

          return  $data->GetAccountResponse->AccountId;
      }

      return '';
  }

    /**
     * GetAuthenticatedTicket method
     * Get an Authenticated ticket for an action to do after user validation.
     * @param  string $Email
     * @param  string $Password
     * @param  string $Action
     * @param  string $Duration
     * @param  string $Remember
     * @return string
     */
    public function GetAuthenticatedTicket($Email, $Password, $Action, $Duration, $Remember)
    {
        $data = $this->_mng->GetAuthenticatedTicket($Email, $Password, $Action, $Duration, $Remember);
        $this->_code = $data->GetAuthenticatedTicketResponse->ReturnCode;
        $this->_onError = ($this->_code != BDI_RETURN_OK);
        if (!$this->_onError) {
            $this->_data = $data->GetAuthenticatedTicketResponse->AuthenticatedTicket;
        }
    }

    /**
     * getTicket method
     * Get a simple ticket for an action to do.
     * @param  string $email
     * @param  string $action
     * @param  string $duration
     * @return string
     */
    public function getTicket($email, $action, $duration)
    {
        $data = $this->_mng->getTicket($email, $action, $duration);
        $this->_code = $data->GetTicketResponse->ReturnCode;
        $this->_onError = ($this->_code != BDI_RETURN_OK);
        if (!$this->_onError) {
            $this->_data = $data->GetTicketResponse->Ticket;
        }

        return '';
    }

    /**
     * CheckAccountStatus method
     * Get the status of user by Email
     * @param  string $email
     * @return string
     */
    public function CheckAccountStatus($email)
    {
        $data = $this->_mng->CheckAccountStatus($email);

        $this->_code = $data->CheckAccountStatusResponse->ReturnCode;
        $this->_onError = ($this->_code != BDI_RETURN_OK);
        if (!$this->_onError) {
            $this->_data = $data->CheckAccountStatusResponse->StatusCode;

            return  $data->CheckAccountStatusResponse->AccountId;
        }

        return '';
    }

    /**
     * CreateAccount method
     * This method creates a user account by passing the properties values
     * @param  array  $accountProperties
     * @return object
     */
    public function CreateAccount($accountProperties)
    {
        $data = $this->_mng->CreateAccount($accountProperties);
        $this->_code = $data->CreateAccountResponse->ReturnCode;
        $this->_onError = ($this->_code != BDI_RETURN_OK);
        if (!$this->_onError) {
            $this->_data = $data->CreateAccountResponse->AccountProperties;

            return  $data->CreateAccountResponse->AccountId;
        }

        return '';
    }

    /**
     * Subscribe method
     * This method add a susbcsription to an user, by passing a ticket.
     * @param  array  $ticket
     * @return string
     */
    public function Subscribe($ticket)
    {
        $data = $this->_mng->Subscribe($ticket);

        $this->_code = $data->SuscribeResponse->ReturnCode;
        $this->_onError = ($this->_code != BDI_RETURN_OK);
        if (!$this->_onError) {
            $this->_data = $data->SuscribeResponse->StatusCode;
        }
    }

    /**
     * ActivatesAccount method
     * This method active a user account, by passing a ticket.
     * @param  array  $ticket
     * @return string
     */
    public function ActivatesAccount($ticket)
    {
        $data = $this->_mng->ActivatesAccount($ticket);
        $this->_code = $data->ActivatesAccountResponse->ReturnCode;
        $this->_onError = ($this->_code != BDI_RETURN_OK);
        if (!$this->_onError) {
            $this->_data = $data->ActivatesAccountResponse->AccessToken;
        }
    }

    /**
     * SetPassword method
     * This method update the user password, by passing a ticket.
     * @param  array  $password
     * @param  array  $ticket
     * @return string
     */
    public function SetPassword($password, $ticket)
    {
        $data = $this->_mng->SetPassword($password, $ticket);
        $this->_code = $data->SetPasswordResponse->ReturnCode;
        $this->_onError = ($this->_code != BDI_RETURN_OK);
        if (!$this->_onError) {
            $this->_data = $data->SetPasswordResponse->ReturnCode;
        }
    }

    /**
     * UpdateAccount method
     * This method update user's prperties account, by passing a new properties and an authenticated ticket.
     * @param  array  $accountProperties
     * @param  string $AuthenticatedTicket
     * @return object
     */
    public function UpdateAccount($accountProperties, $AuthenticatedTicket)
    {
        $data = $this->_mng->UpdateAccount($accountProperties, $AuthenticatedTicket);
        $this->_code = $data->UpdateAccountResponse->ReturnCode;
        $this->_onError = ($this->_code != BDI_RETURN_OK);
        if (!$this->_onError) {
            $this->_data = $data->UpdateAccountResponse->AccountProperties;
        }
    }

    /**
     * Unsubscribe method
     * This method unsuscribe the user's to the request sitecode.
     * @param  string $AuthenticatedTicket
     * @return string
     */
    public function Unsubscribe($AuthenticatedTicket)
    {
        $data = $this->_mng->Unsubscribe($AuthenticatedTicket);
        $this->_code = $data->UnsubscribeResponse->ReturnCode;
        $this->_onError = ($this->_code != BDI_RETURN_OK);
        if (!$this->_onError) {
            $this->_data = $data->UnsubscribeResponse->StatusCode;
        }
    }

    /**
     * CheckToken method
     * This method checks the veracity of the ticket
     * @param  string $Ticket
     * @return string
     */
    public function CheckToken($Ticket)
    {
        $data = $this->_mng->CheckToken($Ticket);
        $this->_code = $data->CheckTokenResponse->ReturnCode;
        $this->_onError = ($this->_code != BDI_RETURN_OK);
        if (!$this->_onError) {
            $this->_data = $data->CheckTokenResponse->ReturnCode;
        }
    }

    /**
     * CheckSiteSubscriptions method
     * @param  string $Ticket
     * @param  string $Culture
     * @return string
     */
    public function CheckSiteSubscriptions($Ticket, $Culture)
    {
        $data = $this->_mng->CheckSiteSubscriptions($Ticket, $Culture);
        $this->_code = $data->CheckSiteSubscriptionsResponse->ReturnCode;
        $this->_onError = ($this->_code != BDI_RETURN_OK);
        if (!$this->_onError) {
            $this->_data = $data->CheckSiteSubscriptionsResponse->SubscriptionObjects->SubscriptionObject;
        }
    }

    public function getElement($ticket, $culture, $datatype = '')
    {
        $data = $this->_mng->getElement($ticket, $culture, $datatype);
        if ($datatype == 'xml') {
            $this->_data = $data;
        } else {
            $this->_code = $data->GetElementResponse->errorResponse;
            $this->_onError = ($this->_code != BDI_RETURN_OK);
            if (!$this->_onError) {
                $this->_data = $data->GetElementResponse;
            }
        }
    }

    public function PushElement($instance, $ticket)
    {
        $data = $this->_mng->PushElement($instance, $ticket);
        $this->_code = $data->PushElementResponse->errorResponse;
        $this->_onError = ($this->_code != BDI_RETURN_OK);
        if (!$this->_onError) {
            $this->_data = $data->PushElementResponse->instance;
        }
    }

    public function UpdateRelationShip($instance, $ticket)
    {
        $data = $this->_mng->UpdateRelationShip($instance, $ticket);
        $this->_code = $data->UpdateRelationshipResponse->errorResponse;
        $this->_onError = ($this->_code != BDI_RETURN_OK);
        if (!$this->_onError) {
            $this->_data = $data->UpdateRelationshipResponse->instance;
        }
    }

    public function SubscribeNewsletter($NewsletterCode, $ticket, $activesubscription = 0)
    {
        $data = $this->_mng->SubscribeNewsletter($NewsletterCode, $ticket, $activesubscription);
        $this->_code = $data->SubscribeNewsletterResponse->ReturnCode;
        $this->_onError = ($this->_code != BDI_RETURN_OK);
        if (!$this->_onError) {
            $this->_data = $data->SubscribeNewsletterResponse->ReturnCode;
        }
    }

    public function UnsubscribeNewsletter($NewsletterCode, $ticket)
    {
        $data = $this->_mng->UnsubscribeNewsletter($NewsletterCode, $ticket);
        $this->_code = $data->UnsubscribeNewsletterResponse->ReturnCode;
        $this->_onError = ($this->_code != BDI_RETURN_OK);
        if (!$this->_onError) {
            $this->_data = $data->UnsubscribeNewsletterResponse->StatusCode;
        }
    }
}
