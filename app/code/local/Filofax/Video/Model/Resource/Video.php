<?php

class Filofax_Video_Model_Resource_Video extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() 
    {
        $this->_init('filovid/video', 'entity_id');
    }
}
