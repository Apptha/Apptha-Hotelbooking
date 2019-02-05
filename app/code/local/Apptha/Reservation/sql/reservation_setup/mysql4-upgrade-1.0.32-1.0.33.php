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
	$setup->removeAttribute('catalog_product', 'apptha_hotel_email');
	$setup->removeAttribute('catalog_product', 'apptha_hotel_address');
}catch(Exception $E) {
}

$setup->addAttribute('catalog_product', 'apptha_hotel_email', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Hotel Information',
	'label'         => 'Email',
	'input'         => 'text',
	'class'         => 'validate-email',
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
	'default_value' => '',
	'visible'       => true,
	'required'      => true,
	'user_defined'  => false,
	'default'       => '',
	'apply_to'      => 'hotel',
	'visible_on_front' => false
));



$setup->addAttribute('catalog_product', 'apptha_hotel_address', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Hotel Information',
	'label'         => 'Address',
	'input'         => 'textarea',
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

$installer->run("
	ALTER TABLE {$this->getTable('reservation/hotel_stock')} ADD `quote_id` INT(10);
	ALTER TABLE {$this->getTable('reservation/hotel_stock')} ADD `order_id` INT(10);
	");


$installer->endSetup();