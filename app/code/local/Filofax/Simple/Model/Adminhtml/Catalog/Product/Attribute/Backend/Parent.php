<?php

class Filofax_Simple_Model_Adminhtml_Catalog_Product_Attribute_Backend_Parent extends Mage_Eav_Model_Entity_Attribute_Backend_Array
{

    public function beforeSave($object)
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        $data = $object->getData($attributeCode);
        if (is_array($data)) {
            $data = array_filter($data);
            $object->setData($attributeCode, implode(',', $data));
        }

        Mage::log($object->getData('thumbnail'));
        return parent::beforeSave($object);
    }
}
