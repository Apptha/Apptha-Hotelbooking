<?php
/*
    Document   : config.xml
    Created on : July 14, 2011, 12:58 PM
    Author     : John
    Description:
        Purpose of the document follows.
*/
$installer = $this;

/* $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');


$setup = $this;

/* Create table for exclude days*/
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('reservation/exclude_days')};

	CREATE TABLE {$this->getTable('reservation/exclude_days')} (
		`id` INT( 11 ) NOT NULL auto_increment,
		`entity_id` INT( 11 ) NOT NULL ,
		`store_id` INT( 11 ) NOT NULL ,
		`period_type` ENUM( 'single', 'recurrent_day', 'period' ) NOT NULL ,
		`period_from` DATE NOT NULL ,
		`period_to` DATE NOT NULL ,
		PRIMARY KEY ( `id` ) ,
		KEY `store_id` (`store_id`),
		INDEX ( `entity_id` , `period_type` , `period_from` , `period_to` )
		
	) DEFAULT CHARSET utf8 ENGINE = InnoDB;
	");



$installer->endSetup();