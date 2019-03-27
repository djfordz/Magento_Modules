<?php

class Filofax_Affiliate_Helper_Data extends Mage_Core_Helper_Data
{
    public function isEnabled()
    {
        return Mage::getStoreConfig('filoaffiliate/general/enable');
    }

    public function getConfigData($key)
    {
        return Mage::getStoreConfig('filoaffiliate/general/' . $key);
    }

    public function getConfigValue($value, $key = null)
    {
        return Mage::getStoreConfig('filoaffiliate/' . $value . "/" . $key);
    }
}
