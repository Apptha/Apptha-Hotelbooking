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
	$setup->removeAttribute('catalog_product', 'apptha_hotel_period_from');
}catch(Exception $E) {

}

$setup->addAttribute('catalog_product', 'apptha_hotel_period_from', array(
	'backend'       => 'eav/entity_attribute_backend_datetime',
	'source'        => '',
	'group'		=> 'Availability Dates/Timings',
	'label'         => 'Active from',
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

$setup->addAttribute('catalog_product', 'apptha_hotel_period_to', array(
	'backend'       => 'eav/entity_attribute_backend_datetime',
	'source'        => '',
	'group'		=> 'Availability Dates/Timings',
	'label'         => 'Active till',
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

$setup->addAttribute('catalog_product', 'apptha_hotel_check_out', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Availability Dates/Timings',
	'label'         => 'Check Out TIme',
	'input'         => 'text',
	'class'         => '',
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
	'default_value' => '',
	'visible'       => true,
	'required'      => true,
	'user_defined'  => false,
	'default'       => '11:59 PM',
	'apply_to'      => 'hotel',
	'visible_on_front' => false
));

$setup->addAttribute('catalog_product', 'apptha_hotel_check_in', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Availability Dates/Timings',
	'label'         => 'Check In Time',
	'input'         => 'text',
	'class'         => '',
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
	'default_value' => '',
	'visible'       => true,
	'required'      => true,
	'user_defined'  => false,
	'default'       => '11:59 AM',
	'apply_to'      => 'hotel',
	'visible_on_front' => false
));

$setup->addAttribute('catalog_product', 'apptha_hotel_order_key', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Hotel Information',
	'label'         => 'Unique order key for hotel',
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


$installer->endSetup();