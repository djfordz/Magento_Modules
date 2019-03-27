<?php

class Filofax_Upsells_Model_Mysql4_Upsells extends Mage_Core_Model_Mysql4_Abstract
{

    public function _construct()
    {
        $this->_init('filoupsell/upsells', 'upsell_id');
    }

    public function getProducts($ruleId)
    {
        $read   = $this->_getReadAdapter();
        $tbl    = $this->getTable('ambannerslite/banners');
        $select = $read->select()->from($tbl, 'ambannerslite_banner_products')->where('rule_id = ?', $ruleId);

        $col = $read->fetchCol($select);

        return $col;
    }
}
