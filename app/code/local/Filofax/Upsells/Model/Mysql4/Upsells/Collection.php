<?php

class Filofax_Upsells_Model_Mysql4_Upsells_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('filoupsell/upsells');
    }
}
