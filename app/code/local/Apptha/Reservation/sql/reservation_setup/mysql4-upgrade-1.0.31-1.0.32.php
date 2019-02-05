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
	$setup->removeAttribute('catalog_product', 'apptha_hotel_rooms_available');
	$setup->removeAttribute('catalog_product', 'apptha_hotel_rooms_deal');
	$setup->removeAttribute('catalog_product', 'apptha_hotel_room_amenities');
}catch(Exception $E) {

}


$installer->endSetup();