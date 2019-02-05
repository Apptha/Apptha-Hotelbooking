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
    $setup->removeAttribute('catalog_product', 'apptha_hotel_room_type');
}catch(Exception $E) {

}

$setup->addAttribute('catalog_product', 'apptha_hotel_room_type', array(
	'backend'       => 'reservation/product_backend_roomtypes',
	'source'        => '',
	'group'		=> 'Rules',
	'label'         => 'Room types/Prices',
	'input'         => 'text',
	'class'         => 'validate-emailSender',
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
	'default_value' => '',
	'visible'       => true,
	'required'      => false,
	'user_defined'  => false,
	'default'       => '',
	'apply_to'      => 'hotel',
	'visible_on_front' => false
));


/* Create table for exclude days*/
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('reservation/room_types')};

	CREATE TABLE {$this->getTable('reservation/room_types')} (
		`id` INT NOT NULL auto_increment,
		`entity_id` INT( 11 ) NOT NULL ,
		`store_id` INT( 11 ) NOT NULL ,
		`room_type` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
		`room_price` DECIMAL(12,4) NOT NULL ,
		`inclusions` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_bin  NOT NULL ,
		PRIMARY KEY ( `id` ) ,
		KEY `store_id` (`store_id`),
		INDEX ( `entity_id` , `room_type`( 100 ) , `room_price` , `inclusions`( 200 ) )
		
	) DEFAULT CHARSET utf8 ENGINE = InnoDB;
	");



$installer->endSetup();