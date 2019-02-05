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
	$setup->removeAttribute('catalog_product', 'apptha_hotel_deal_from');
	$setup->removeAttribute('catalog_product', 'apptha_hotel_room_price_mode');
	$setup->removeAttribute('catalog_product', 'apptha_hotel_deal_day');
}catch(Exception $E) {

}

$installer->run("
	ALTER TABLE {$this->getTable('reservation/room_types')} ADD `room_for_deal` INT (10) NULL;
	");


$installer->endSetup();