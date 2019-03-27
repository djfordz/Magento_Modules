<?php
/**
 * Created by PhpStorm.
 * User: dford
 * Date: 10/25/18
 * Time: 8:42 AM
 */
require_once 'Mage/Checkout/controllers/CartController.php';

/**
 * Class Filofax_Caadiscount_PostController
 */
class Filofax_Caadiscount_PostController extends Mage_Checkout_CartController
{
    /**
     *
     */
    const COUPON_CODE = 'AUTO_GENERATION_%s - CAA Membership Discount';
    /**
     * Validates against CAA Web Service and adds discount.
     * @throws Mage_Exception
     */
    public function indexAction()
    {
        /**
         * No reason continue with empty shopping cart
         */
        if (!$this->_getCart()->getQuote()->getItemsCount()) {
            $this->_goBack();
            return;
        }

        /**
         * Get Membership Id from frontend
         */
        $membershipId = $this->getRequest()->getPost('caadiscount_code');

        if ($this->getRequest()->getParam('remove') == 1) {
            $this->getMembership()->deleteCoupon($membershipId);
            $this->_getSession()->addSuccess($this->__('Cancelled CAA Discount.'));
            $this->_goBack();
            return;
        }

        /**
         * Validate Membership Id.
         */

        $idLength = strlen($membershipId);
        $isIdLengthValid = $idLength && $idLength <=
            Filofax_Caadiscount_Helper_Data::CAA_DISCOUNT_MAX_LENGTH;


        if ($isIdLengthValid) {
            try {
                $member =  $this->getMembership()->validateMember($membershipId);
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $this->_goBack();
                return;
            }

            if (isset($member->ValidateMemberResult->MembershipStatus) && $member->ValidateMemberResult->MembershipStatus === 'A') {
                try {
                    $this->getMembership()->addDiscount($membershipId);
                    $this->_getSession()->addSuccess($this->__('CAA Discount was applied.'));
                    $this->_getSession()->setCartDiscountCode($membershipId);
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }



            } else {
                $this->_getSession()->addError($this->__('CAA Membership ID not valid, Please contact CAA for help.'));
            }
        }

        $this->_goBack();
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    protected function getMembership()
    {
        return Mage::getModel('filocaa/membership');
    }
}