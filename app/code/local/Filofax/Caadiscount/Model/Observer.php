<?php
/**
 * Created by PhpStorm.
 * User: dford
 * Date: 10/25/18
 * Time: 9:41 AM
 */

class Filofax_Caadiscount_Model_Observer
{
    /**
     * @param Varien_Event_Observer $observer
     */
    public function sendSale(Varien_Event_Observer $observer)
    {
        if (!$this->_helper()->isEnabled(Filofax_Caadiscount_Model_Abstract::DISCOUNT)) {
            return;
        }
        $location = $this->_helper()->getEndpoint(Filofax_Caadiscount_Model_Abstract::DISCOUNT);
        $username = $this->_helper()->getUserId(Filofax_Caadiscount_Model_Abstract::DISCOUNT);
        $password = $this->_helper()->getPassword(Filofax_Caadiscount_Model_Abstract::DISCOUNT);
        $order = $observer->getEvent()->getOrder();
        $membershipId = $order->getCouponCode();

        if (isset($membershipId)) {
            $headers = array();
            $headers[] = new SoapHeader('http://www.w3.org/2005/08/addressing', 'Action',  'https://uddi.caasco.ca/AxisWS/AffinityServiceProxy/IAffinityServiceProxy/UploadSingleRecord', true);
            $headers[] = new SoapHeader('http://www.w3.org/2005/08/addressing', 'To', rtrim($location, '?wsdl'), false);

            $client = $this->getClient()->soapClient($location);
            Mage::log($client->__getFunctions(), null, 'caa_discount.log');
            $client->__setSoapHeaders($headers);

            try {
                $result = $client->UploadSingleRecord(
                    array(
                        '_partnerData' => array(
                            'AccountNumber'             => $this->_helper()->getAccountNumber(),
                            'AmountSpend'               => (float)$order->getGrandTotal(),
                            'ClubCreditValue'           => 0,
                            'DateAndTime'               => date('Y-m-d', time()),
                            'DollarsSavedOnThisSale'    => (float)abs($order->getDiscountAmount()),
                            'MemberCreditValue'         => 0,
                            'NoOfLitresPremiumFuel'     => 0,
                            'NoOfLitresRegularFuel'     => 0,
                            'Reserved3'                 => 0.00,
                            'SaleLocation'              => $username,
                            'SalesType1'                => (float)$order->getSubtotal(),
                            'SalesType2'                => 0.00,
                            'SalesType3'                => 0.00,
                            'TotalDiscountAmount'       => (float)abs($order->getDiscountAmount()),
                            'TransactionNum'            => $order->getId(),
                            'TransactionType'           => 'Regular',
                            'UniformMemberNumber'       => $order->getCouponCode()
                        ), '_securityToken' => array(
                                'ApplicationID'         => $username,
                                'ApplicationPassword'   => $password
                            )
                    )
                );

            } catch (Exception $e) {
                Mage::logException($e);
                if ($this->_helper()->isDebug()) {
                    Mage::log($client->__getLastRequest(), null, 'caa_discount.log');
                    Mage::log($client->__getLastResponse(), null, 'caa_discount.log');
                }

            }

            if (isset($result)) {
                Mage::log('Successfully applied CAA Discount.', null, 'caa_discount.log');
            }
            $this->getMembership()->deleteCoupon($membershipId);
        }
    }

    /**
     * @return Filofax_Caadiscount_Helper_Data|Mage_Core_Helper_Abstract
     */
    protected function _helper()
    {
        return Mage::helper('filocaa');
    }

    /**
     * @return false|Filofax_Caadiscount_Model_Client
     */
    protected function getClient()
    {
        return Mage::getSingleton('filocaa/client');
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    protected function getMembership()
    {
        return Mage::getModel('filocaa/membership');
    }
}