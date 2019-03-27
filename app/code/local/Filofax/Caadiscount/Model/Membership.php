<?php
/**
 * Created by PhpStorm.
 * User: dford
 * Date: 10/25/18
 * Time: 2:10 PM
 */
class Filofax_Caadiscount_Model_Membership extends Filofax_Caadiscount_Model_Abstract
{


    /**
     * @param $id
     * @return bool|object
     * @throws Mage_Exception
     */
    public function validateMember($id)
    {
        if (!$this->_helper()->isEnabled(self::MEMBERSHIP)) {
            return;
        }

        $member = false;

        $location = $this->_helper()->getEndpoint(self::MEMBERSHIP);
        $username = $this->_helper()->getUserId(self::MEMBERSHIP);
        $password = $this->_helper()->getPassword(self::MEMBERSHIP);

        $headers = array();
        $headers[] = new SoapHeader('http://www.w3.org/2005/08/addressing', 'Action', 'https://uddi.caasco.ca/MembershipValidationWS/MembershipValidationService/IMembershipValidation/ValidateMember', true);
        $headers[] = new SoapHeader('http://www.w3.org/2005/08/addressing', 'To', $location, false);

        $client = $this->getClient()->soapClient($location);
        $client->__setSoapHeaders($headers);

        try {
            $member = $client->ValidateMember(array('MembershipNumber' => $id, 'ESBSecurityToken' => array('ApplicationID' => $username, 'ApplicationMethodName' => 'ValidateMember', 'ApplicationPassword' => $password)));

        } catch (Exception $e) {
            if ($this->_helper()->isDebug()) {
                Mage::log($client->__getLastRequest(), null, 'caa_discount.log');
                Mage::log($client->__getLastResponse(), null, 'caa_discount.log');
            }
            Mage::logException($e);
        }

        if (isset($member)) {
            try {
                Mage::register('MembershipID', $id);
            } catch (Mage_Core_Exception $e) {
                throw new Mage_Exception('Could not register MembershipID');
            }

            return $member;
        }

        return false;

    }

    /**
     * @param $membershipId
     */
    public function addDiscount($membershipId)
    {
        /**
         * Add Coupon Code.
         */
        try {
            $codeLength = strlen($membershipId);
            $isCodeLengthValid = $codeLength && $codeLength <=
                Filofax_Caadiscount_Helper_Data::CAA_DISCOUNT_MAX_LENGTH;

            if ($this->createSalesRule($membershipId)) {
                $model = Mage::getModel('salesrule/rule')
                    ->getCollection()
                    ->addFieldToFilter('name', array('eq' => sprintf(self::COUPON_CODE,
                        $membershipId)))
                    ->getFirstItem();

                $couponCode = $model->getCode();

                if (isset($couponCode) && $couponCode == $membershipId) {
                    $this
                        ->_getQuote()
                        ->getShippingAddress()
                        ->setCollectShippingRates(true);

                    $this
                        ->_getQuote()
                        ->setCouponCode($isCodeLengthValid ? $couponCode : '')
                        ->setDiscountCode($isCodeLengthValid ? $couponCode : '')
                        ->collectTotals()
                        ->save();

                    if ($isCodeLengthValid && $this->_getQuote()->getCouponCode()) {
                        return true;
                    }
                } else {
                    $this->deleteCoupon($membershipId);
                }
            }
        } catch (Exception $e) {
            $this->_getSession()->addError($this->__('Cannot apply the CAA Discount. Contact us for help.'));
            Mage::logException($e);
        }

        return false;
    }


    /**
     * @return Filofax_Caadiscount_Helper_Data|Mage_Core_Helper_Abstract
     */
    public function _helper()
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
}
