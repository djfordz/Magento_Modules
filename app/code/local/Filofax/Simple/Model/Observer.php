<?php

class Filofax_Simple_Model_Observer
{
    public function beforeBlockToHtml(Varien_Event_Observer $observer)
    {
        $grid = $observer->getBlock();

        if ($grid instanceof Mage_Adminhtml_Block_Catalog_Product_Grid) {
            Mage::log('hits!!!!!!!!!!!!!!!!!!', null, 'test.log');
            $grid->addColumnAfter(
                'parent_product_sku', array(
                'header' => Mage::helper('filofax_simple')->__('Parent Product Sku'),
                'name' => 'parent_product_sku',
                'index' => 'parent_product_sku',
                'sortable' => true
                ), 'entity_id'
            );
        }
    }

    public function beforeCollectionLoad(Varien_Event_Observer $observer)
    {
        $collection = $observer->getCollection();
        if (!isset($collection)) {
            return;
        }

        /**
         * Mage_Customer_Model_Resource_Customer_Collection
         */
        if ($collection instanceof Mage_Catalog_Model_Resource_Product_Collection) {
            /* @var $collection Mage_Customer_Model_Resource_Customer_Collection */
            $collection->addAttributeToSelect('parent_product_sku');
        }

        return $this;
    }
}
