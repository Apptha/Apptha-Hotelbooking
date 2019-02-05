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
	ALTER TABLE {$this->getTable('reservation/hotel_orders')} ADD `quote_id` INT( 5 ) NOT NULL, ADD INDEX ( `quote_id` );
	ALTER TABLE {$this->getTable('reservation/hotel_orders')} CHANGE  `room_price` `order_total` DECIMAL( 12,4 ) NOT NULL;
	ALTER TABLE {$this->getTable('reservation/hotel_orders')} CHANGE  `persons` `guests` INT( 5 ) NOT NULL;
	ALTER TABLE {$this->getTable('reservation/hotel_orders')} CHANGE  `quantity` `rooms` INT( 5 ) NOT NULL;
	ALTER TABLE {$this->getTable('reservation/hotel_orders')} ADD  `order_item_id` INT( 5 ) NOT NULL , ADD INDEX ( `order_item_id` );
	");

$installer->endSetup();