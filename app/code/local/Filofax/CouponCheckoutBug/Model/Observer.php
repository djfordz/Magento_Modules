<?php
/**
 * Created by PhpStorm.
 * User: dford
 * Date: 11/20/18
 * Time: 2:50 PM
 */

class Filofax_CouponCheckoutBug_Model_Observer
{
    public function removeCoupon(Varien_Event_Observer $observer)
    {
        $controller = $observer->getControllerAction();
        if ($controller->getRequest()->getParam('remove') == 1) {
            Mage::getSingleton("checkout/session")->unsetData('cart_coupon_code');
        }
        return $this;
    }
}