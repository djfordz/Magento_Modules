<?php

class Filofax_Upsells_Helper_Data extends Mage_Core_Helper_Data
{
    public function isEnabled()
    {
        return Mage::getStoreConfig('filoupsell/general/enable');
    }
    
}
