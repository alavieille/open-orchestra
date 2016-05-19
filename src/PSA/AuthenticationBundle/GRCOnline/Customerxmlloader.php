<?php

/**
 * This class parse with XPath Library the  Customer XML datas.
 *
 * @category  Psa_Dsin
 * @package   Psa_Dsin_GRCOnline
 * @copyright  Copyright (c) 2013 PSA
 * @license   PSA
 */
class Psa_Dsin_GRCOnline_Customerxmlloader
{
  protected $_xml;

    public function __construct($datas)
    {
        $this->_xml = new SimpleXMLElement($datas);
        $this->_xml->registerXPathNamespace("soap",    "http://schemas.xmlsoap.org/soap/envelope/");
        $this->_xml->registerXPathNamespace("ns1",    "http://mpsa.com/Services/bend/CRMService");
        $this->_xml->registerXPathNamespace("ns2",    "http://mpsa.com/Dcr/CRMService/");
    }

    public function getUserprofile()
    {
        $userprofile = array();
        $value = $this->_xml->xpath('//ns2:element[@name=\'userprofile\']/ns2:data');
        if (!empty($value)) {
            foreach ($value as $data) {
                $valuesi = '';
                $codesi = (string) $data["codesi"];

                foreach ($data->value as $item) {
                    $valuesi = (string) $item;
                }
                foreach ($data->item as $item) {
                    $valuesi = (isset($item->label)) ? (string) $item->label : (string) $item["key"];
                }
                $userprofile[$codesi] = $valuesi;
            }
        }

        return $userprofile;
    }

    public function getDataRelatedVehicles()
    {
        $relatedVehicles = array();
        $elements = $this->_xml->xpath('//ns2:instance/ns2:element/ns2:node[@name=\'relatedvehicles\']/ns2:element');
        if (!empty($elements)) {
            foreach ($elements as $data) {
                $tmpRelationType = $tmpDtRelease = $tmpDtType = $tmpDateRelease = '';
                $_RelationType = $_DtRelease = $_VehDate = $_DtRelationType = '';

                if ($data->reference['name'] == 'vehicle') {
                    $oidvehicule = (string) $data->reference->byId['oidref'];
                }
                if ($data->node['name'] == 'lifecycle') {
                    foreach ($data->node->element[count($data->node->element)-1]->data as $_data) {
                        $codesi = (string) $_data['codesi'];
                        if ($codesi == 'REL_VEH_RELEASE') {
                            $_VehDate = (string) $_data->value['lastupdate'];
                            $_DtRelease = (string) $_data->value;
                        }
                        if ($codesi == 'REL_VEH_TYPE') {
                            $_RelationType = (string) $_data->item[0]['key'];
                            $_DtRelationType = (string) $_data->item[0]['lastupdate'];
                        }

                        if ($_DtRelationType > $tmpDtType) {
                            $tmpDtType = $_DtRelationType;
                            $tmpRelationType = $_RelationType;
                        }

                        if ($_VehDate > $tmpDateRelease) {
                            $tmpDateRelease = $_VehDate;
                            $tmpDtRelease = $_DtRelease;
                        }
                    }
                }
                $relveh = array('REL_VEH_TYPE' => $tmpRelationType, 'REL_VEH_RELEASE' => $tmpDtRelease, 'OID' => $oidvehicule);
                if ($relveh != null) {
                    $relveh['VEHICULE'] = $this->getDataVehicules($relveh['OID']);
                    $relatedVehicles[] = array('veh' => $relveh, 'reldate' => $relveh['REL_VEH_RELEASE']);
                }
            }
        }

        return $relatedVehicles;
    }

    public function getDataVehicules($oid)
    {
        $vehinfo = array();
        $elements = $this->_xml->xpath('//ns2:instance/ns2:node[@name=\'vehicles\']/ns2:element');
        if (!empty($elements)) {
            foreach ($elements as $data) {
                if ((string) $data['oid'] == $oid) {
                    foreach ($data->data as $ousrinfos) {
                        $codesi = (string) $ousrinfos['codesi'];
                        $vehinfo[$codesi] = (string) $ousrinfos->value;
                    }
                }
            }
        }

        return $vehinfo;
    }

    public function getRecentDateActivity()
    {
        $TmpDate = null;
        $elements = $this->_xml->xpath('//ns2:instance/ns2:element');
        if (!empty($elements)) {
            foreach ($elements as $element) {
                $_value = (string) $element["lastupdate"];
                if ($TmpDate < $_value) {
                    $TmpDate = $_value;
                }
            }
        }
        $nodeelts = $this->_xml->xpath('//ns2:instance/ns2:node/ns2:element');
        if (!empty($nodeelts)) {
            foreach ($nodeelts as $elt) {
                $_value = (string) $elt["lastupdate"];
                if ($TmpDate < $_value) {
                    $TmpDate = $_value;
                }
            }
        }

        return $TmpDate;
    }

    public function getDataRelatedGeosites()
    {
        $geosites = array();
        $elements = $this->_xml->xpath('//ns2:instance/ns2:element/ns2:node[@name=\'relatedgeosites\']/ns2:element');
        if (!empty($elements)) {
            foreach ($elements as $data) {
                $oidsite = null;
                $_RelationType = '';

                if (isset($data->reference) && $data->reference['name'] == 'geosite') {
                    $oidsite = (string) $data->reference->byId['oidref'];
                }
                $_RelationType = (string) $data['name'];
                if ($oidsite != null) {
                    $_geosite = $this->getDataGeosite($oidsite);
                    $_geosite['REL_PREFERED_DEALER_TYPE'] = $_RelationType;
                    $geosites[] = $_geosite;
                }
            }
        }

        return $geosites;
    }

    public function getDataGeosite($oid)
    {
        $geosites = array();
        $elements = $this->_xml->xpath('//ns2:instance/ns2:node[@name=\'geosites\']/ns2:element');
        if (!empty($elements)) {
            foreach ($elements as $data) {
                if ((string) $data['oid'] == $oid) {
                    foreach ($data->data as $ousrinfos) {
                        $codesi = (string) $ousrinfos['codesi'];
                        $geosites[$codesi] = (string) $ousrinfos->value;
                    }
                }
            }
        }

        return $geosites;
    }

    public function getDataRelatedInterestProductVehicle()
    {
        $vehicles = array();
        $elements = $this->_xml->xpath('//ns2:instance/ns2:element/ns2:node[@name=\'relatedinterest\']/ns2:element');

        if (!empty($elements)) {
            foreach ($elements as $data) {
                $oidsite = null;
                $_RelationType = '';
                if (isset($data->reference) && $data->reference['name'] == 'interestproductvehicle') {
                    $oidsite = (string) $data->reference->byId['oidref'];
                }
                if ($data->data['codesi'] == 'REL_PREFERED_DEALER_TYPE') {
                    $_RelationType = (string) $data->data->item[0]['key'];
                }
                if ($oidsite != null) {
                    $_geosite = $this->getDataGeosite($oidsite);
                    $_geosite['REL_PREFERED_DEALER_TYPE'] = $_RelationType;
                    $vehicles[$oidsite] = $_geosite;
                }
            }
        }

        return $vehicles;
    }

    public function getDataInterestProductVehicle($oid)
    {
        $vehicleinfo = array();
        $elements = $this->_xml->xpath('//ns2:instance/ns2:node[@name=\'interest\']/ns2:node[@name=\'interestproduct\']/ns2:element[@name=\'interestproductvehicle\']'); //interestproductvehicle
    if (!empty($elements)) {
        foreach ($elements as $data) {
            if ((string) $data['oid'] == $oid) {
                foreach ($data->data as $ousrinfos) {
                    $codesi = (string) $ousrinfos['codesi'];
                    $vehicleinfo[$codesi] = (string) $ousrinfos->value;
                }
            }
        }
    }

        return $vehicleinfo;
    }

    public function getSubscriptions()
    {
        $sbs = array();
        $elements = $this->_xml->xpath('//ns2:instance/ns2:element/ns2:node[@name=\'subscriptions\']/ns2:element');
        if (!empty($elements)) {
            foreach ($elements as $data) {
                $sb = array();
                foreach ($data as $dt) {
                    $sb[(string) $dt['codesi']] = (string) $dt->value;
                }
                $sbs[] = $sb;
            }
        }

        return $sbs;
    }
}
