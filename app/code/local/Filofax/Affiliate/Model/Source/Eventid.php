<?php

class Filofax_Affiliate_Model_Source_Eventid extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
     
    public function getAllOptions()
    {
		return Mage::getModel('filoaffiliate/abstract')->getEventIds();
    }
 
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }
 
    
}
