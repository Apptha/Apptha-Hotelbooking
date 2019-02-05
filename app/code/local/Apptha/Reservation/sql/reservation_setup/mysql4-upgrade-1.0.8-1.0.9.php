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
    $setup->removeAttribute('catalog_product', 'apptha_hotel_terms_conditions');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_map');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_contact_no');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_fax_no');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_slogan');
}catch(Exception $E) {

}

$setup->addAttribute('catalog_product', 'apptha_hotel_terms_conditions', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Hotel Information',
	'label'         => 'Terms & Conditions',
	'input'         => 'textarea',
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

$setup->addAttribute('catalog_product', 'apptha_hotel_map', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Hotel Information',
	'label'         => 'Map',
	'input'         => 'textarea',
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

$setup->addAttribute('catalog_product', 'apptha_hotel_contact_no', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Hotel Information',
	'label'         => 'Contact number',
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

$setup->addAttribute('catalog_product', 'apptha_hotel_fax_no', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Hotel Information',
	'label'         => 'Fax',
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

$setup->addAttribute('catalog_product', 'apptha_hotel_slogan', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Hotel Information',
	'label'         => 'Slogan',
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

/* Create table for exclude days*/


$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('reservation/hotel_deal')};

	CREATE TABLE {$this->getTable('reservation/hotel_deal')} (
		`id` INT( 11 ) NOT NULL auto_increment,
		`entity_id` INT( 11 ) NOT NULL ,
		`store_id` INT( 11 ) NOT NULL ,
		`room_type_id` INT( 11 ) NOT NULL ,
		`room_type_name` VARCHAR( 150 ) NULL ,
		`deal_special_price` DECIMAL(12,4) NOT NULL ,
		`rooms_for_deal` INT(4) NOT NULL ,
		`date_from` DATE NOT NULL ,
		`date_to` DATE NOT NULL ,
		`deal_status` INT( 4 ) NOT NULL ,
		PRIMARY KEY ( `id` ) ,
		KEY `store_id` (`store_id`),
		INDEX ( `entity_id` , `room_type_id`, `deal_special_price`  )
		
	) DEFAULT CHARSET utf8 ENGINE = InnoDB;
	");

$installer->endSetup();