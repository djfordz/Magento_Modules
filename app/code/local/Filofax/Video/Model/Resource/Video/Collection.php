<?php

class Filofax_Video_Model_Resource_Video_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    protected function _construct() 
    {
        $this->_init('filovid/video');
    }
}
