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
    $setup->removeAttribute('catalog_product', 'apptha_hotel_period_to');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_city');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_website');
}catch(Exception $E) {

}

$setup->addAttribute('catalog_product', 'apptha_hotel_period_to', array(
	'backend'       => 'eav/entity_attribute_backend_datetime',
	'source'        => '',
	'group'		=> 'Hotel Information',
	'label'         => 'Period to',
	'input'         => 'date',
	'class'         => '',
	'type'          => 'datetime',
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
	'default_value' => '',
	'visible'       => true,
	'required'      => true,
	'user_defined'  => false,
	'default'       => '',
	'apply_to'      => 'hotel',
	'visible_on_front' => false
));

$setup->addAttribute('catalog_product', 'apptha_hotel_city', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Hotel Information',
	'label'         => 'City',
	'input'         => 'multiselect',
	'class'         => '',
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
	'default_value' => '',
	'visible'       => true,
	'required'      => true,
	'user_defined'  => false,
	'default'       => '',
	'apply_to'      => 'hotel',
	'visible_on_front' => false
));

$setup->addAttribute('catalog_product', 'apptha_hotel_website', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Hotel Information',
	'label'         => 'Website',
	'input'         => 'text',
	'class'         => 'validate-url',
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
	'default_value' => '',
	'visible'       => true,
	'required'      => true,
	'user_defined'  => false,
	'default'       => '',
	'apply_to'      => 'hotel',
	'visible_on_front' => false
));

$setup->addAttribute('catalog_product', 'apptha_hotel_facilites', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Hotel Information',
	'label'         => 'Facilities(Seperate with commas.)',
	'input'         => 'text',
	'class'         => '',
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
	'default_value' => '',
	'visible'       => true,
	'required'      => false,
	'user_defined'  => false,
	'default'       => '',
	'apply_to'      => 'hotel',
	'visible_on_front' => false
));



$installer->run("
	ALTER TABLE {$this->getTable('reservation/hotel_orders')} ADD `room_type_id` INT( 5 ) NOT NULL, ADD INDEX ( `room_type_id` );
	ALTER TABLE {$this->getTable('reservation/hotel_orders')} ADD `period_from` DATETIME;
	ALTER TABLE {$this->getTable('reservation/hotel_orders')} ADD `period_to` DATETIME;
	ALTER TABLE {$this->getTable('reservation/hotel_orders')} ADD `persons` INT ( 5 ) NOT NULL;
	");



$installer->endSetup();