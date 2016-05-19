<?php

/**
 * PSA SA
 *
 * LICENSE
 *
 */

/**
 * Base class that represent a PHP Factory back-office PSA user.
 *
 * @category  Psa_Dsin
 * @package   Psa_Dsin_GRCOnline_CustomerAt
 * @copyright  Copyright (c) 2013 PSA
 * @license   PSA
 */
class Psa_Dsin_GRCOnline_CustomerAt_Subscription
{
    public $ConsumerCode;
    public $CreatedBy;
    public $IsActif;
    public $ReleaseDate;
    public $SubscriptionCode;
    public $CancelationDate = null;

    public $customerMng;

    /**
     * Construtor of the Psa_Dsin_GRCOnline_CustomerAt_Dealer class
     */
    public function __construct($datas = null)
    {
        $this->customerMng    = new Psa_Dsin_GRCOnline_Customermanager(PUBLIC_PATH.'/Wsdl/CRMDirect.wsdl');
        if ($datas != null) {
            foreach ($datas as $key => $data) {
                switch ($key) {
                    case Psa_Dsin_GRCOnline_Customerfields::SBS_CONSUMER_CODE:
                        $this->ConsumerCode = $data;
                        break;
                    case Psa_Dsin_GRCOnline_Customerfields::SBS_CREATED_BY:
                        $this->CreatedBy = $data;
                        break;
                    case Psa_Dsin_GRCOnline_Customerfields::SBS_IS_ACTIVE:
                        $this->IsActif = $data;
                        break;
                    case Psa_Dsin_GRCOnline_Customerfields::SBS_RELEASE_DATE:
                        $this->ReleaseDate = $data;
                        break;
                    case Psa_Dsin_GRCOnline_Customerfields::SBS_SUBSCRIPTION_CODE:
                        $this->SubscriptionCode = $data;
                        break;
                    case Psa_Dsin_GRCOnline_Customerfields::SBS_CANCELATION_DATE:
                        $this->CancelationDate = $data;
                        break;
                }
            }
        }
    }

    public function addSubscription($token, $activesubscription = 0)
    {
        $this->customerMng->SubscribeNewsletter($this->SubscriptionCode, $token, $activesubscription);
        if ($this->customerMng->onError()) {
            return false;
        }

        return true;
    }

    public function deleteSubscription($token)
    {
        $this->customerMng->UnsubscribeNewsletter($this->SubscriptionCode, $token);
        if ($this->customerMng->onError()) {
            return false;
        }

        return true;
    }
}
