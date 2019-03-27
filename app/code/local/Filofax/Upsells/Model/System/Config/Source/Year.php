<?php

class Filofax_Upsells_Model_System_Config_Source_Year
{

    protected $_options;

    public function getAllOptions($withEmpty = true, $defaultValues = false)
    {

        $attribute = Mage::getModel('eav/entity_attribute')
            ->loadByCode(Mage_Catalog_Model_Product::ENTITY, 'year');

        return $attribute->getSource()->getAllOptions();

    }

    public function toOptionArray($isMultiselect = false)
    {
        if (!$this->_options) {
            $this->_options = $this->getAllOptions();
        }

        $options = $this->_options;

        if (!$isMultiselect) {
            array_unshift($options, array('value' => '', 'label' => Mage::helper('adminhtml')->__('--Please Select--')));
        }

        return $options;
    }
} 
