<?php
/**
 * Created by PhpStorm.
 * User: dford
 * Date: 10/23/18
 * Time: 2:52 PM
 */
class Filofax_Caadiscount_Block_Discount extends Mage_Core_Block_Template
{
    public function getFormActionUrl()
    {
        return $this->getUrl('caadiscount/post', array('_secure' => $this->_isSecure()));
    }

    public function getDiscountCode()
    {
        return $this->getQuote()->getCouponCode();
    }

    protected function getQuote()
    {
        return Mage::getSingleton('checkout/cart')->getQuote();
    }
}