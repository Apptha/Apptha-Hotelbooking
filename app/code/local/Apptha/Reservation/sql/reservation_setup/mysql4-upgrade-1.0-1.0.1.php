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


try {
    $setup->removeAttribute('catalog_product', 'apptha_hotel_name');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_about');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_website');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_email');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_address');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_map');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_contact_no');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_fax_no');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_slogan');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_terms_conditions');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_period_from');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_period_to');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_rate');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_deal_day');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_deal_from');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_deal_to');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_shipping');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_city');
    $setup->removeAttribute('catalog_product', 'apptha_hotel_country');

}catch(Exception $E) {

}

$setup = $this;

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
	'required'      => true,
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
	'required'      => true,
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
	'required'      => true,
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
	'required'      => true,
	'user_defined'  => false,
	'default'       => '',
	'apply_to'      => 'hotel',
	'visible_on_front' => false
));

$setup->addAttribute('catalog_product', 'apptha_hotel_terms_conditions', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Hotel Information',
	'label'         => 'Terms & Conditions',
	'input'         => 'text',
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

$setup->addAttribute('catalog_product', 'apptha_hotel_period_from', array(
	'backend'       => 'eav/entity_attribute_backend_datetime',
	'source'        => '',
	'group'		=> 'Hotel Information',
	'label'         => 'Period from',
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
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Hotel Information',
	'label'         => 'Period To',
	'input'         => 'date',
	'class'         => '',
	'type'          => 'datetime',
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
	'default_value' => '',
	'visible'       => true,
	'required'      => false,
	'user_defined'  => false,
	'default'       => '',
	'apply_to'      => 'hotel',
	'visible_on_front' => false
));



$setup->addAttribute('catalog_product', 'apptha_hotel_deal_day', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Deal',
	'label'         => 'Deal of day',
	'input'         => 'boolean',
	'class'         => '',
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
	'default_value' => 0,
	'visible'       => true,
	'required'      => false,
	'user_defined'  => false,
	'default'       => '0',
	'apply_to'      => 'hotel',
	'visible_on_front' => false
));

$setup->addAttribute('catalog_product', 'apptha_hotel_deal_from', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Deal',
	'label'         => 'Deal from',
	'input'         => 'date',
	'class'         => '',
	'type'          => 'datetime',
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
	'default_value' => '',
	'visible'       => true,
	'required'      => false,
	'user_defined'  => false,
	'default'       => '',
	'apply_to'      => 'hotel',
	'visible_on_front' => false
));

$setup->addAttribute('catalog_product', 'apptha_hotel_deal_to', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Deal',
	'label'         => 'Deal To',
	'input'         => 'date',
	'class'         => '',
	'type'          => 'datetime',
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
	'default_value' => '',
	'visible'       => true,
	'required'      => false,
	'user_defined'  => false,
	'default'       => '',
	'apply_to'      => 'hotel',
	'visible_on_front' => false
));

$setup->addAttribute('catalog_product', 'apptha_hotel_shipping', array(
	'backend'       => '',
	'source'        => '',
	'group'		=> 'Hotel Information',
	'label'         => 'Shipping',
	'input'         => 'boolean',
	'class'         => '',
	'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
	'default_value' => 1,
	'visible'       => true,
	'required'      => false,
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
	'input'         => 'text',
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

$setup->addAttribute('catalog_product', 'apptha_hotel_country', array(
	'backend'       => '',
	'source'        => '',
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