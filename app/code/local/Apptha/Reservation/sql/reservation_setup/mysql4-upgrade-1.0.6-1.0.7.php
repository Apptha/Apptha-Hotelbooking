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

$setup->addAttribute('catalog_product', 'apptha_hotel_postal_code', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Hotel Information',
	'label'         => 'Postal/Zip code',
	'input'         => 'text',
	'class'         => 'validate-alphanum',
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
	'default_value' => '',
	'visible'       => true,
	'required'      => true,
	'user_defined'  => false,
	'default'       => '',
	'apply_to'      => 'hotel',
	'visible_on_front' => false
));


/* Create table for exclude days*/



$installer->endSetup();