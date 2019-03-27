<?php
/**
 * Created by PhpStorm.
 * User: dford
 * Date: 3/4/19
 * Time: 8:59 AM
 */

$installer = $this;
$installer->startSetup();

/**
 * Create column response in review/review_detail table'
 */
$installer->getConnection()
    ->addColumn($installer->getTable('review/review_detail'), 'response', array('type' => Varien_Db_Ddl_Table::TYPE_TEXT, 'size' => 32, 'nullable' => true, 'comment' => 'Filofax Response'));

$installer->endSetup();