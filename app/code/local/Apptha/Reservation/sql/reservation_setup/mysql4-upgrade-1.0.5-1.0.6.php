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
    $setup->removeAttribute('catalog_product', 'apptha_hotel_room_type_price');
}catch(Exception $E) {

}

/* Create table for exclude days*/
$installer->run("
	ALTER TABLE {$this->getTable('reservation/room_types')} ADD `room_quantity` INT( 5 ) NOT NULL;
	ALTER TABLE {$this->getTable('reservation/room_types')} ADD `room_capacity` INT( 8 ) NOT NULL;
	ALTER TABLE {$this->getTable('reservation/room_types')} ADD `room_price_per_night` DECIMAL( 12,4 ) NOT NULL;
	ALTER TABLE {$this->getTable('reservation/room_types')} CHANGE  `room_price` `room_price_per_person` DECIMAL( 12,4 ) NOT NULL;
	ALTER TABLE {$this->getTable('reservation/room_types')} ADD  `room_price_per_extra_person` DECIMAL( 12,4 ) NOT NULL;
	");



$installer->endSetup();