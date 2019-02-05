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
	DROP TABLE IF EXISTS {$this->getTable('reservation/hotel_orders')};

	CREATE TABLE {$this->getTable('reservation/hotel_orders')} (
		`id` INT( 11 ) NOT NULL auto_increment,
		`entity_id` INT( 11 ) NOT NULL ,
		`store_id` INT( 11 ) NOT NULL ,
		`order_id` INT( 11 ) NOT NULL ,
		`increment_id` INT( 11 ) NOT NULL ,
		`room_type` VARCHAR( 150 ) NOT NULL ,
		`hotel_name` VARCHAR( 150 ) NOT NULL ,
		`room_price` DECIMAL(12,4) NOT NULL ,
		`quantity` DECIMAL(12,4) NOT NULL ,
		`sku` VARCHAR(50) NOT NULL ,
		`date_ordered` DATETIME NOT NULL ,
		PRIMARY KEY ( `id` ) ,
		KEY `store_id` (`store_id`),
		INDEX ( `entity_id` , `order_id`, `increment_id` , `room_price`, `sku` )
		
	) DEFAULT CHARSET utf8 ENGINE = InnoDB;
	");

$installer->endSetup();