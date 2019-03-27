<?php

$installer = $this;
$installer->startSetup();

$installer->run("
    DROP TABLE IF EXISTS {$this->getTable('filovid/video')};
    CREATE TABLE {$this->getTable('filovid/video')} (
		`entity_id` int(20) NOT NULL AUTO_INCREMENT,
		`product` int(100) NOT NULL UNIQUE,
		`youtube` varchar(1000) DEFAULT NULL,
		PRIMARY KEY (`entity_id`)                                         
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;   
");
$installer->endSetup();
