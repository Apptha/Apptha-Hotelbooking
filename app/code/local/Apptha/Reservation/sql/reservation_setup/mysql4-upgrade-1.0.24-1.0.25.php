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
}catch(Exception $E) {

}

$setup->addAttribute('catalog_product', 'apptha_hotel_rooms_available', array(
	'backend'       => '',
	'source'        => '',
	'group'			=> 'Prices',
	'label'         => 'Rooms Available',
	'input'         => 'text',
	'class'         => 'validate-digits',
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
	'default_value' => '',
	'visible'       => true,
	'required'      => false,
	'user_defined'  => false,
	'default'       => '',
	'apply_to'      => 'hotel',
	'visible_on_front' => false
));

$setup->addAttribute('catalog_product', 'apptha_hotel_rooms_deal', array(
	'backend'       => '',
	'source'        => '',
	'group'			=> 'Prices',
	'label'         => 'Rooms Available For Special Price',
	'input'         => 'text',
	'class'         => 'validate-digits',
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
	'default_value' => '',
	'visible'       => true,
	'required'      => false,
	'user_defined'  => false,
	'default'       => '',
	'apply_to'      => 'hotel',
	'visible_on_front' => false
));




$installer->endSetup();