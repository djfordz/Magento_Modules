<?php

class Filofax_Locale_Model_Adminhtml_System_Config_Source_Language
{

    protected $_options;

    public function toOptionArray($Multiselect = false) {
        return Mage::app()->getLocale()->getOptionLocales(); 
    }
}
