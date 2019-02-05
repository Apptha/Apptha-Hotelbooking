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
	$setup->removeAttribute('catalog_product', 'apptha_hotel_city');
}catch(Exception $E) {

}

$setup->addAttribute('catalog_product', 'apptha_hotel_city', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Hotel Information',
	'label'         => 'City',
	'input'         => 'select',
	'type'          => 'text',
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
	'default_value' => '',
	'visible'       => true,
	'required'      => true,
        'default'       => '',
	'apply_to'      => 'hotel',
	'visible_on_front' => false,
         'option' => array(
        'value' => array('newyork' => array(0 => 'New York', 1 => 'New York'), 'Hartford' => array(0 => 'Hartford', 1 => 'Hartford'), 'Cleveland' => array(0 => 'Cleveland', 1 => 'Cleveland')),
        'order' => array('newyork' => '0', 'Hartford' => '1', 'Cleveland' => '2')
    ),
));


$installer->endSetup();