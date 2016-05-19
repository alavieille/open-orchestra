<?php
/**
 * PSA SA
 *
 * LICENSE
 *
 */

/**
 * Base class that represent a CPPv2 customer-user which contains BDI's, user and cars's informations.
 * This class is a mock object that do not use an internet connection but a local file.
 * It has the same interface as the User class
 *
 * @category  Psa_Dsin
 * @package   Psa_Dsin_GRCOnline_CustomerAt
 * @copyright  Copyright (c) 2013 PSA
 * @license   PSA
 */
class Psa_Dsin_GRCOnline_CustomerAt_UserMock
{
    public $MOCK_DIR_FILE;

    public $Email = null; //Email du compte
    public $Gender = null; // genre de l'utilisateur
    public $Civility = null; // genre de l'utilisateur
    public $IsCustomer = false; // est il client de la marque ACNT_IS_CUSTOMER (true ou false)
    public $CustomerDate = null; // date à laquelle il a été client pour la 1ere fois
    public $LastBoughtVehicle = null; // objet de type Vehicle : dernier véhicule acheté qu'il possède encore (statut owner ou pending)
    public $DrivedVehicles = null; // Tout les véhicules utilisé (statut owner, user ou pending)
    public $AllVehicles = null; // Tout les véhicules quelques soit le statut (owner, user, pending ou old)
    public $LastUpdateDate = null;
    public $DealerVN = null;
    public $DealerAPV = null;

    public function loadUser($email, $culture = 'fr-FR')
    {
        $data = unserialize(file_get_contents(PUBLIC_PATH.'/mock/UserMock.'.md5($email).'.dat'));
        $this->Email = $data->Email;
        $this->Gender = $data->Gender;
        $this->Civility = $data->Civility;
        $this->IsCustomer = $data->IsCustomer;
        $this->CustomerDate = $data->CustomerDate;
        $this->LastBoughtVehicle = $data->LastBoughtVehicle;
        $this->DrivedVehicles = $data->DrivedVehicles;
        $this->AllVehicles = $data->AllVehicles;
        $this->LastUpdateDate = $data->LastUpdateDate;
        $this->DealerVN = $data->DealerVN;
        $this->DealerAPV = $data->DealerAPV;
    }

    public function addDealer($accesstoken, Psa_Dsin_GRCOnline_CustomerAt_Dealer $dealer = null)
    {
        $this->deleteDealer($dealer, $accesstoken);
        if ($dealer->Type == 'VN') {
            $this->DealerVN[] = $dealer;
        }
        if ($dealer->Type == 'APV') {
            $this->DealerAPV[] = $dealer;
        }
    }

    public function deleteDealer(Psa_Dsin_GRCOnline_CustomerAt_Dealer $dealer, $accesstoken)
    {
        if ($dealer->Type == 'VN') {
            $this->DealerVN = array();
        }
        if ($dealer->Type == 'APV') {
            $this->DealerAPV = array();
        }
    }
}
