<?php

class Filofax_Video_Model_Video extends Mage_Core_Model_Abstract {

    protected function _construct() {
        $this->_init('filovid/video');
    }

    public function loadByProductId($product_id) {
        $collection = $this->getCollection()->addFieldToFilter('product', $product_id);
        return $collection;
    }

}
