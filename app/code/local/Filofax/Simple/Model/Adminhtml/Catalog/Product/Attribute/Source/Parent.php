<?php

class Filofax_Simple_Model_Adminhtml_Catalog_Product_Attribute_Source_Parent extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions($withEmpty = true, $defaultValues = false)
    {
        if (is_null($this->_options)) {
            $simpleProduct = Mage::registry('current_product');
            if ($simpleProduct !== null) {
                $parentSkus = $this->getParentSkus($simpleProduct);

                $productSkus = array();
                $productSkus[$parentSkus[0]] = $parentSkus[0];

                $this->_options = $productSkus;
            }
        }

        return $this->_options;
    }

    public function getProductOptions($product)
    {
        if (is_null($this->_options)) {
            $parentSkus = $this->getParentSkus($product);

            $productSkus = array();
            $productSkus[$parentSkus[0]] = $parentSkus[0];
        }

        return $productSkus;
    }
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }

    public function addValueSortToCollection($collection, $dir = 'asc')
    {
        $adminStore  = Mage_Core_Model_App::ADMIN_STORE_ID;
        $valueTable1 = $this->getAttribute()->getAttributeCode() . '_t1';
        $valueTable2 = $this->getAttribute()->getAttributeCode() . '_t2';

        $collection->getSelect()->joinLeft(
            array($valueTable1 => $this->getAttribute()->getBackend()->getTable()),
            "`e`.`entity_id`=`{$valueTable1}`.`entity_id`"
            . " AND `{$valueTable1}`.`attribute_id`='{$this->getAttribute()->getId()}'"
            . " AND `{$valueTable1}`.`store_id`='{$adminStore}'",
            array()
        );

        if ($collection->getStoreId() != $adminStore) {
            $collection->getSelect()->joinLeft(
                array($valueTable2 => $this->getAttribute()->getBackend()->getTable()),
                "`e`.`entity_id`=`{$valueTable2}`.`entity_id`"
                . " AND `{$valueTable2}`.`attribute_id`='{$this->getAttribute()->getId()}'"
                . " AND `{$valueTable2}`.`store_id`='{$collection->getStoreId()}'",
                array()
            );
            $valueExpr = new Zend_Db_Expr("IF(`{$valueTable2}`.`value_id`>0, `{$valueTable2}`.`value`, `{$valueTable1}`.`value`)");

        } else {
            $valueExpr = new Zend_Db_Expr("`{$valueTable1}`.`value`");
        }

        $collection->getSelect()
            ->order($valueExpr, $dir);

        Mage::log($this);
        return $this;
    }

    public function getFlatColums()
    {
        $columns = array(
            $this->getAttribute()->getAttributeCode() => array(
                'type'      => 'varchar',
                'is_null'   => true,
                'default'   => null,
                'extra'     => null
            )
        );
        return $columns;
    }


    public function getFlatUpdateSelect($store)
    {
        return Mage::getResourceModel('eav/entity_attribute')
            ->getFlatUpdateSelect($this->getAttribute(), $store);
    }

    protected function getParentIds($product)
    {
        $id = $product->getId();
        $type = $product->getTypeId();

        $parentIds = null;

        if ($type == 'simple') {
            $parentIds = Mage::getResourceSingleton('catalog/product_type_configurable')
                ->getParentIdsByChild($id);
        }

        if ($parentIds) {
            return $parentIds;
        } else {
            return false;
        }
    }

   /**
     * Returns parent skus
     *
     * @param Mage_Catalog_Model_Product $product
     * @param bool $asString if set to 'true', skus as comma separated string.
     */

    protected function getParentSkus($product){

        $parentIds = $this->getParentIds($product);

        $products = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect(array('sku'))
            ->addIdFilter($parentIds);

        $parentSkus = null;

        foreach ($products as $product) {
            $parentSkus[]=$product->getSku();
        }

        return $parentSkus;
    }
}
