<?php

class Filofax_Simple_Model_Resource_Mysql4_Setup extends Mage_Catalog_Model_Resource_Setup
{

    protected function _prepareValues($attr)
    {
        $data = parent::_prepareValues($attr);
        $data = array_merge($data, array(
                'apply_to' => $this->_getValue($attr, 'apply_to'),
            )
        );

        return $data;
    }

    public function getDefaultEntities()
    {
        return array(
                'catalog_product' => array(
                    'entity_model'      => 'catalog/product',
                    'attribute_model'   => 'catalog/resource_eav_attribute',
                    'table'             => 'catalog/product',
                    'additional_attribute_table' => 'catalog/eav_attribute',
                    'entity_attribute_collection' => 'catalog/product_attribute_collection',
                    'attributes' => array(
                        'parent_product_sku' => array(
                            'group'                      => 'General',
                            'type'                       => 'varchar',
                            'backend'                    => 'eav/entity_attribute_backend_array',
                            'frontend'                   => '',
                            'label'                      => 'Parent Product Sku',
                            'note'                       => 'Configurable Product this product is attahed to',
                            'input'                      => 'select',
                            'class'                      => '',
                            'source'                     => 'filofax_simple/adminhtml_catalog_product_attribute_source_parent',
                            'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
                            'visible'                    => true,
                            'required'                   => false,
                            'user_defined'               => false,
                            'default'                    => '',
                            'searchable'                 => false,
                            'filterable'                 => true,
                            'comparable'                 => false,
                            'visible_on_front'           => false,
                            'visible_in_advanced_search' => false,
                            'used_in_product_listing'    => false,
                            'used_for_sort_by'           => false,
                            'unique'                     => false,
                            'apply_to'                   => 'simple',
                        ),
                    ),
                ),
            );
    }
}
