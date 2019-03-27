<?php

$installer = $this;
$installer->startSetup();
$installer->addAttributeGroup('catalog_product', 'Default', 'Filofax Collections Options', 9000);
$installer->addAttribute('catalog_category', 'collections_image', array(
	 'group'         => 'General Information',
	 'sort_order'    => 200,
	 'input'         => 'image',
	 'type'          => 'varchar',
	 'label'         => 'Collections Image',
	 'backend'       => 'catalog/category_attribute_backend_image',
	 'visible'       => 1,
	 'required'      => 0,
	 'user_defined'  => 1,
	 'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));
$installer->endSetup();


