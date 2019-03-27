<?php

class Filofax_Affiliate_Block_Affiliate extends Mage_Core_Block_Template
{
    public function getTrackingCode()
    {
        if (Mage::helper('filoaffiliate')->getConfigData('enable') == 0) {
            return;
        }

        return Mage::getModel('filoaffiliate/affiliate')->getTrackingCode();
    }
}
