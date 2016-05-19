<?php

/**
 * This class defines all the methods to access to the Customer@ Service
 *
 * @category DataService
 * @package Customer
 * @author e379819
 */
class Psa_Dsin_Dataservice_Customer extends Psa_Dsin_Dataservice_Abstract
{
    protected function getSoapLocation($config)
    {
        return $config->dcr->bend->crmservice;
    }

    /**
     * ActivatesAccount method
     *
     * @param  string $ticket
     * @param  string $siteCode
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
     * ActivatesAccount method
     *
     * @param  string $ticket
     * @param  string $siteCode
     * @return object return the object response or an error message
     */
    public function getTicket($email, $sitecode, $action, $duration)
    {
        $params = array(
                'parameters' => array(
                        'GetTicketRequest' => array(
                            'SiteCode'    => $sitecode,
                            'Email'    => $email,
                            'Action'    => $action,
                            'Duration'    => $duration,
                        ),
                ),
        );

        return $this->_call('GetTicket', $params);
    }
}
