<?php

/**
 * PSA SA
 *
 * LICENSE
 *
 */

/**
 * Base class that represent a CPPv2 Customer@  interest vehicle
 *
 * @category  Psa_Dsin
 * @package   Psa_Dsin_GRCOnline_CustomerAt
 * @copyright  Copyright (c) 2013 PSA
 * @license   PSA
 */
class Psa_Dsin_GRCOnline_CustomerAt_InterestVehicle
{
    public $LCDV;
    public $LabelModel;
    public $LabelVersion;
    public $LabelTrimLevel;
    public $CodeTrimLevel;
    public $CodeRange;
    public $Order;

    /**
     * Construtor of the Psa_Dsin_GRCOnline_CustomerAt_InterestVehicle class
     */
    public function __construct($datas)
    {
        $_OrderDate = '';
        foreach ($datas as $key => $data) {
            switch ($key) {
                case Psa_Dsin_GRCOnline_Customerfields::IPV_LCVD :
                    $this->LCDV = $data;
                    break;
                case Psa_Dsin_GRCOnline_Customerfields::IPV_LABELMODEL:
                    $this->LabelModel = $data;
                    break;
                case Psa_Dsin_GRCOnline_Customerfields::IPV_LABELVERSION:
                    $this->LabelVersion = $data;
                    break;
                case Psa_Dsin_GRCOnline_Customerfields::IPV_LABELTRIMLEVEL:
                    $this->LabelTrimLevel = $data;
                    break;
                case Psa_Dsin_GRCOnline_Customerfields::IPV_CODETRIMLEVEL:
                    $this->CodeTrimLevel = $data;
                    break;
                case Psa_Dsin_GRCOnline_Customerfields::IPV_CODERANGE:
                    $this->CodeRange = $data;
                    break;
                case Psa_Dsin_GRCOnline_Customerfields::IPV_ORDER:
                    $this->Order = $data;
                    break;
            }
        }
    }
}
