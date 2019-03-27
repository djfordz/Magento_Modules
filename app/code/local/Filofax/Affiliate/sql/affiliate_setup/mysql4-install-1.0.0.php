<?php
  
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$setup->addAttribute('catalog_product', 'event_id', array(
    'group'         => 'General',
    'input'         => 'select',
    'type'          => 'varchar',
    'label'         => 'Event ID',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' 	=> 0,
    'searchable' 	=> 0,
    'filterable' 	=> 0,
    'source'		=> 'filoaffiliate/source_eventid',
    'comparable'    => 0,
    'visible_on_front' => 0,
    'visible_in_advanced_search'  => 0,
    'is_html_allowed_on_front' => 0,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

