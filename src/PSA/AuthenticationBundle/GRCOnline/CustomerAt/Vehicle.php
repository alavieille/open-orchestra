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
class Psa_Dsin_GRCOnline_CustomerAt_Vehicle
{
    public $OrderDate; // date de commande (VEH_DEALER_CUSTOMER_ORDER_DATE ou VEH_DISTRI_CUSTOMER_ORDER_DATE suivant celle qui existe)
    public $DeliveryDate;// Date de livraison (VEH_DELIVERY_DATE)
    public $ReleaseDate;// Date de mise en circulation (VEH_DATE_OF_RELEASE)
    public $RegistrationDate;// date d'immatriculation (VEH_REGISTRATATION_DATE)
    public $UserSinceDate;// utilisateur depuis quand (voir où trouver l'info)
    public $Range;// Gamme 'vp' ou 'vu' VEH_GENDER
    public $LCDV;// Les 6 premiers caracteres de VEH_LCDV
    public $LCDV16;// VEH_LCDV complet
    public $WarantyEndDate;// date de fin de garantie VEH_WARRANTY_END_DATE.
    public $Relation;// 'REL_VEH_TYPE'


    /**
     * Construtor of the Psa_Dsin_GRCOnline_CustomerAt_Vehicle class
     */
    public function __construct($datas)
    {
        $_OrderDate = '';
        foreach ($datas['veh'] as $key => $data) {
            if (is_array($data)) {
                foreach ($data as $vehkey => $vehdata) {
                    switch ($vehkey) {
                        case 'REL_VEH_TYPE':
                            $this->Relation = $vehdata;
                            break;
                        case 'VEH_WARRANTY_END_DATE':
                            $this->WarantyEndDate = $vehdata;
                            break;
                        case 'VEH_LCDV':
                            $this->LCDV16 = $vehdata;
                            break;
                        case 'VEH_GENDER':
                            $this->Range = $vehdata;
                            break;
                        case 'VEH_DATE_OF_RELEASE':
                            $this->ReleaseDate = $vehdata;
                            break;
                        case 'VEH_REGISTRATION_DATE':
                            $this->RegistrationDate = $vehdata;
                            break;
                        case 'VEH_DELIVERY_DATE':
                            $this->DeliveryDate = $vehdata;
                            break;
                        case 'VEH_DEALER_CUSTOMER_ORDER_DATE':
                            $this->OrderDate = $vehdata;
                            break;
                        case 'VEH_DISTRI_CUSTOMER_ORDER_DATE':
                            $_OrderDate = $vehdata;
                            break;
                    }
                }
            } else {
                switch ($key) {
                    case 'REL_VEH_TYPE':
                        $this->Relation = $data;
                        break;
                }
            }
        }
        $this->UserSinceDate = $datas['reldate'];
        if ($this->LCDV16 !== '' && strlen($this->LCDV16) >= 6) {
            $this->LCDV = substr($this->LCDV16, 0, 6);
        }
        if ($this->OrderDate == '' && $_OrderDate != '') {
            $this->OrderDate = $_OrderDate;
        }
    }

    public function GetBoughtDate()
    {
        if ($this->RegistrationDate != '') {
            return $this->RegistrationDate;
        }
        if ($this->DeliveryDate != '') {
            return $this->DeliveryDate;
        }
        if ($this->OrderDate != '') {
            return $this->OrderDate;
        }
        if ($this->ReleaseDate != '') {
            return $this->ReleaseDate;
        }
    }

    public function CheckLastBoughtVehicle($veh)
    {
        return $this->GetBoughtDate() > $veh->GetBoughtDate();
    }

    public function CheckFirstBoughtVehicle($veh)
    {
        return $this->GetBoughtDate() < $veh->GetBoughtDate();
    }
}
