<?php

class Filofax_Affiliate_Model_System_Config_Source_Category
{
    public function getAllOptions()
    {
    	$attributes = Mage::getModel('filoaffiliate/affiliate')->getAttributeOptions();

    	$options[0] = 'Disabled';

    	usort($attributes, function($a, $b) {return strcmp($a->getFrontendLabel(), $b->getFrontendLabel());});

		foreach($attributes as $attribute) {
            if($attribute->getFrontendLabel() == '') {
                continue;
            }

            $attributeCode = $attribute->getAttributeCode();
			$options[$attributeCode] = $attribute->getFrontendLabel() . ' (' . $attribute->getAttributeCode() . ')';
		}
        return $options;
    }	
    
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }

}
