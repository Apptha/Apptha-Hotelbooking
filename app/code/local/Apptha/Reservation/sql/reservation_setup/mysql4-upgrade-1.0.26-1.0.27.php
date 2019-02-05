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
	DROP TABLE IF EXISTS {$this->getTable('reservation/hotel_stock')};
	
	CREATE TABLE {$this->getTable('reservation/hotel_stock')} (
		`id` INT( 11 ) NOT NULL auto_increment,
		`entity_id` INT( 11 ) NOT NULL ,
		`store_id` INT( 11 ) NOT NULL ,
		`is_rooms` INT ( 4 ) NOT NULL ,
		`room_id` INT( 11 ) NOT NULL ,
		`rooms_available` INT( 11 ) NOT NULL ,
		`period_date` DATE NOT NULL ,
		`period_date_timestamp` BIGINT UNSIGNED NOT NULL ,
		PRIMARY KEY ( `id` ) ,
		KEY `store_id` (`store_id`),
		INDEX ( `entity_id` , `room_id` , `period_date`, `period_date_timestamp` )
		
	) DEFAULT CHARSET utf8 ENGINE = InnoDB;  
    ");

$installer->endSetup();