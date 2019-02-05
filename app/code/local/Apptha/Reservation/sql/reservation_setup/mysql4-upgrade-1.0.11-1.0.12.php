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
    $setup->removeAttribute('catalog_product', 'apptha_hotel_country');
}catch(Exception $E) {

}

$setup->addAttribute('catalog_product', 'apptha_hotel_country', array(
	'backend'       => '',
	'source'        => 'reservation/entity_attribute_source_countries',
	'group'		=> 'Hotel Information',
	'label'         => 'Country',
	'input'         => 'select',
	'class'         => '',
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
	'visible'       => true,
	'required'      => true,
	'user_defined'  => false,
	'default'       => '',
	'apply_to'      => 'hotel',
	'visible_on_front' => false
));


$installer->endSetup();