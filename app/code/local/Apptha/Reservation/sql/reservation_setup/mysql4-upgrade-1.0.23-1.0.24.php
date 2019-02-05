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

//$setup->addAttribute('catalog_product', 'apptha_hotel_deal_from', array(
//	'backend'       => 'eav/entity_attribute_backend_datetime',
//	'source'        => '',
//	'group'		=> 'Rules',
//	'label'         => 'Room Special Price From',
//	'input'         => 'date',
//	'class'         => '',
//    'type'          => 'datetime',
//	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
//	'default_value' => '',
//	'visible'       => true,
//	'required'      => false,
//	'user_defined'  => false,
//	'default'       => '',
//	'apply_to'      => 'hotel',
//	'visible_on_front' => false
//));
//
//$setup->addAttribute('catalog_product', 'apptha_hotel_deal_to', array(
//	'backend'       => 'eav/entity_attribute_backend_datetime',
//	'source'        => '',
//	'group'		=> 'Rules',
//	'label'         => 'Room special Price To',
//	'input'         => 'date',
//	'class'         => '',
//    'type'          => 'datetime',
//	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
//	'default_value' => '',
//	'visible'       => true,
//	'required'      => false,
//	'user_defined'  => false,
//	'default'       => '',
//	'apply_to'      => 'hotel',
//	'visible_on_front' => false
//));


$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('reservation/hotel_inventory')};
	DROP TABLE IF EXISTS {$this->getTable('reservation/hotel_stock')};

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
		`booked_date_from` DATE NOT NULL ,
		`booked_date_to` DATE NOT NULL ,
		`booked_date_from_timestamp` BIGINT UNSIGNED NOT NULL ,
		`booked_date_to_timestamp` BIGINT UNSIGNED NOT NULL ,
		PRIMARY KEY ( `id` ) ,
		KEY `store_id` (`store_id`),
		INDEX ( `entity_id` , `room_id` , `quote_id`, `order_item_id`, `increment_id`, `booked_date_from`, `booked_date_from_timestamp`, `booked_date_to`, `booked_date_to_timestamp` )
		
	) DEFAULT CHARSET utf8 ENGINE = InnoDB; 
	
	CREATE TABLE {$this->getTable('reservation/hotel_stock')} (
		`id` INT( 11 ) NOT NULL auto_increment,
		`entity_id` INT( 11 ) NOT NULL ,
		`store_id` INT( 11 ) NOT NULL ,
		`is_rooms` INT ( 4 ) NOT NULL ,
		`room_id` INT( 11 ) NOT NULL ,
		`rooms_available` INT( 11 ) NOT NULL ,
		`period_date` DATE NOT NULL ,
		`period_date_timestamp` DATE NOT NULL ,
		PRIMARY KEY ( `id` ) ,
		KEY `store_id` (`store_id`),
		INDEX ( `entity_id` , `room_id` , `period_date`, `period_date_timestamp` )
		
	) DEFAULT CHARSET utf8 ENGINE = InnoDB; 
	");

$installer->endSetup();