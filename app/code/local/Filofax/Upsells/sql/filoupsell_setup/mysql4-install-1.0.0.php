<?php

$this->startSetup();

$this->run(
    "CREATE TABLE IF NOT EXISTS `{$this->getTable('filoupsell/upsells')}`(
        `upsell_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `rule_id` INT(10) UNSIGNED NOT NULL,
        `filoupsell_enable` TINYINT(1) NOT NULL DEFAULT '0',
        `filoupsell_product_banner_img` VARCHAR(255) DEFAULT NULL,
        `filoupsell_product_banner_alt` VARCHAR(255) DEFAULT NULL,
        `filoupsell_product_banner_hover_text` VARCHAR(255) DEFAULT NULL,
        `filoupsell_product_banner_link` VARCHAR(255) DEFAULT NULL,
        `filoupsell_product_banner_gift_images_enable` TINYINT(2) NOT NULL DEFAULT '0',
        `filoupsell_product_banner_description` TEXT DEFAULT NULL,
        PRIMARY KEY (`upsell_id`, `rule_id`),
        UNIQUE KEY (`rule_id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
);

$this->endSetup();

$eav = new Mage_Eav_Model_Entity_Setup('core_setup');
$eav->startSetup();
$eav->addAttribute('catalog_product', 'filoupsell_upsell_sku', array(
    'group'                 => 'General',
    'label'                 => 'Yearly Upsell Refill Sku',
    'type'                  => 'varchar',
    'input'                 => 'text',
    'position'              => 1000,
    'required'              => 0,
    'visible_on_front'      => 1,
    'html_allowed_on_front' => 1,
    'filterable'            => 0,
    'searchable'            => 0,
    'comparable'            => 0,
    'user_defined'          => 1,
    'is_configurable'       => 0,
    'global'                => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'default_value'         => '',
    'unique'                => 0,
    'note'                  => 'will show on product page'
));

$eav->endSetup();
