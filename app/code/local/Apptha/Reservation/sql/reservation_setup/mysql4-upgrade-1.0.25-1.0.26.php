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

try {
	
}catch(Exception $E) {

}

$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('reservation/hotel_inventory')};
	
	CREATE TABLE {$this->getTable('reservation/hotel_inventory')} (
		`id` INT( 11 ) NOT NULL auto_increment,
		`entity_id` INT( 11 ) NOT NULL ,
		`store_id` INT( 11 ) NOT NULL ,
		`is_rooms` INT ( 4 ) NOT NULL ,
		`room_id` INT( 11 ) NOT NULL ,
		`rooms` INT( 11 ) NOT NULL ,
		`guests` INT( 11 ) NOT NULL ,
		`customer_id` INT( 11 ) NOT NULL ,
		`quote_id` INT( 11 ) NOT NULL ,
		`order_id` INT( 11 ) NOT NULL ,
		`order_item_id` INT( 11 ) NOT NULL ,
		`increment_id` INT( 20 ) NOT NULL ,
		`booked_date` DATE NOT NULL ,
		`booked_date_timestamp` BIGINT UNSIGNED NOT NULL ,
		PRIMARY KEY ( `id` ) ,
		KEY `store_id` (`store_id`),
		INDEX ( `entity_id` , `room_id` , `quote_id`, `order_item_id`, `increment_id`, `booked_date`, `booked_date_timestamp` )
		
	) DEFAULT CHARSET utf8 ENGINE = InnoDB; 
    ");

$installer->endSetup();