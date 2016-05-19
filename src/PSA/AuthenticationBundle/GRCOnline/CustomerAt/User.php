<?php

/**
 * PSA SA
 *
 * LICENSE
 *
 */

/**
 * Base class that represent a CPPv2 customer-user which contains BDI's, user and cars's informations.
 *
 * @category  Psa_Dsin
 * @package   Psa_Dsin_GRCOnline_CustomerAt
 * @copyright  Copyright (c) 2013 PSA
 * @license   PSA
 */
class Psa_Dsin_GRCOnline_CustomerAt_User
{
    public $Email = null; //Email du compte
    public $Gender = null; // genre de l'utilisateur
    public $Civility = null; // genre de l'utilisateur
    public $USR_LAST_NAME = null; // genre de l'utilisateur
    public $USR_FIRST_NAME = null; // genre de l'utilisateur
    public $USR_ADDR_1 = null; // genre de l'utilisateur
    public $USR_ADDR_2 = null; // genre de l'utilisateur
    public $USR_ADDR_3 = null; // genre de l'utilisateur
    public $USR_ADDR_ZIP_CODE = null; // genre de l'utilisateur
    public $USR_ADDR_CITY = null; // genre de l'utilisateur
    public $USR_ADDR_PROVINCE = null; // genre de l'utilisateur
    public $USR_ADDR_COUNTRY = null; // genre de l'utilisateur
    public $USR_DATE_OF_BIRTH = null; // genre de l'utilisateur
    public $USR_EMAIL_PRO = null; // genre de l'utilisateur
    public $USR_FAMILY_SITUATION = null; // genre de l'utilisateur
    public $USR_JOB = null; // genre de l'utilisateur
    public $USR_VATIM = null; // genre de l'utilisateur
    public $USR_PHONE_HOME = null; // genre de l'utilisateur
    public $USR_PHONE_MOBILE_HOME = null; // genre de l'utilisateur
    public $USR_PHONE_MOBILE_PRO = null; // genre de l'utilisateur
    public $USR_PHONE_PRO = null; // genre de l'utilisateur
    public $USR_PREFERED_LANGUAGE = null; // genre de l'utilisateur

    public $IsCustomer = false; // est il client de la marque ACNT_IS_CUSTOMER (true ou false)
    public $CustomerDate = null; // date à laquelle il a été client pour la 1ere fois
    public $LastBoughtVehicle = null; // objet de type Vehicle : dernier véhicule acheté qu'il possède encore (statut owner ou pending)
    public $LastMainDrivedVehicle = null; // objet de type Vehicle : dernier véhicule acheté qu'il possède encore en statut dégrandant (owner > user > pending)
    public $DrivedVehicles = null; // Tout les véhicules utilisé (statut owner, user ou pending)
    public $AllVehicles = null; // Tout les véhicules quelques soit le statut (owner, user, pending ou old)
    public $LastUpdateDate = null;
    public $DealerVN = null;
    public $DealerAPV = null;
    public $Subscriptions = null;
    public $SubscriptionsActives = null;

    private function getDataCustomer($customerMng, $email, $culture = 'fr-FR')
    {
        $customerMng->getTicket($email, 'GetElement', '86000');    //récupération d'un ticket.
        $ticket = $customerMng->data();

        $customerMng->getElement($ticket, $culture, 'xml');
    }

    private function SetProfileDatas($profileDatas, $email)
    {
        $fields = array('USR_LAST_NAME', 'USR_FIRST_NAME', 'USR_ADDR_1', 'USR_ADDR_2', 'USR_ADDR_3',
                'USR_ADDR_ZIP_CODE', 'USR_ADDR_CITY', 'USR_ADDR_PROVINCE', 'USR_ADDR_COUNTRY', 'USR_DATE_OF_BIRTH',
                'USR_EMAIL_PRO', 'USR_FAMILY_SITUATION', 'USR_JOB', 'USR_VATIM', 'USR_PHONE_HOME',
                'USR_PHONE_MOBILE_HOME', 'USR_PHONE_MOBILE_PRO', 'USR_PHONE_PRO', 'USR_PREFERED_LANGUAGE', );
        if (isset($profileDatas[Psa_Dsin_GRCOnline_Customerfields::USR_GENDER])) {
            $this->Gender = $profileDatas[Psa_Dsin_GRCOnline_Customerfields::USR_GENDER];
        }
        if (isset($profileDatas[Psa_Dsin_GRCOnline_Customerfields::USR_CIVILITY])) {
            $this->Civility = $profileDatas[Psa_Dsin_GRCOnline_Customerfields::USR_CIVILITY];
        }

        foreach ($fields as $field) {
            if (defined('Psa_Dsin_GRCOnline_Customerfields::'.$field)) {
                if (isset($profileDatas[$field])) {
                    $this->$field = $profileDatas[$field];
                }
            }
        }
        $this->Email =    $email;
    }

    public function compare_vehicle(Psa_Dsin_GRCOnline_CustomerAt_Vehicle $a, Psa_Dsin_GRCOnline_CustomerAt_Vehicle $b)
    {
        $statuts = array('OLD' => 0, 'PENDING' => 1, 'USER' => 2,'OWNER' => 3);

        return (($statuts[$a->Relation] < $statuts[$b->Relation]) && $b->CheckLastBoughtVehicle($a));
    }

    public function loadUser($email, $culture = 'fr-FR')
    {
        $customerMng    = new Psa_Dsin_GRCOnline_Customermanager(PUBLIC_PATH.'/Wsdl/CRMDirect.wsdl');
        $this->getDataCustomer($customerMng, $email, $culture);
        if ($customerMng->onError()) {
            return false;
        }

        $xmlloader = new Psa_Dsin_GRCOnline_Customerxmlloader($customerMng->data());

        $this->LastUpdateDate = $xmlloader->getRecentDateActivity();
        $this->SetProfileDatas($xmlloader->getUserprofile(), $email);
        $vehicles = $xmlloader->getDataRelatedVehicles();
        if (count($vehicles)>0) {
            $LastBoughtVehicletmp = null;
            $FirstBoughtVehicleDatetmp = null;
            foreach ($vehicles as $vehicle) {
                $_vehcus = new Psa_Dsin_GRCOnline_CustomerAt_Vehicle($vehicle);
                $this->AllVehicles[] = $_vehcus;
                if ($_vehcus->Relation != 'OLD') {
                    $this->IsCustomer = true;
                    $this->DrivedVehicles[] = $_vehcus;

                    //calcul de la date du véhicule actif le plus ancien
                    if ($FirstBoughtVehicleDatetmp == null) {
                        $FirstBoughtVehicleDatetmp = $_vehcus;
                    } elseif ($_vehcus->CheckFirstBoughtVehicle($FirstBoughtVehicleDatetmp)) {
                        $FirstBoughtVehicleDatetmp = $_vehcus;
                    }

                    //calcul de la date du véhicule actif le plus récent
                    if ($LastBoughtVehicletmp == null) {
                        $LastBoughtVehicletmp = $_vehcus;
                    } elseif ($_vehcus->CheckLastBoughtVehicle($LastBoughtVehicletmp)) {
                        $LastBoughtVehicletmp = $_vehcus;
                    }
                }
            }
            $this->LastBoughtVehicle = $LastBoughtVehicletmp;
            usort($this->AllVehicles, array("Psa_Dsin_GRCOnline_CustomerAt_User", "compare_vehicle"));
            $this->LastMainDrivedVehicle = $this->AllVehicles[0];
            if ($FirstBoughtVehicleDatetmp != null) {
                $this->CustomerDate = $FirstBoughtVehicleDatetmp->GetBoughtDate();
            }
        }
        $dealers = $xmlloader->getDataRelatedGeosites();
        if (count($dealers)>0) {
            foreach ($dealers as $dealer) {
                $_dealercus = new Psa_Dsin_GRCOnline_CustomerAt_Dealer($dealer);
                if ($_dealercus->Type == 'relatedgeositepreferredvn') {
                    $this->DealerVN[] = $_dealercus;
                }
                if ($_dealercus->Type == 'relatedgeositepreferredapv') {
                    $this->DealerAPV[] = $_dealercus;
                }
            }
        }

        //gestion Suscription
        $subscriptions = $xmlloader->getSubscriptions();
        if (count($subscriptions)>0) {
            foreach ($subscriptions as $subscription) {
                $this->Subscriptions[] = new Psa_Dsin_GRCOnline_CustomerAt_Subscription($subscription);
            }
        }
        if (count($this->Subscriptions)>0) {
            foreach ($this->Subscriptions as $Subscription) {
                if (is_null($Subscription->CancelationDate)) {
                    $this->SubscriptionsActives[] = $Subscription;
                }
            }
        }

        return true;
    }
}
