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

$installer->updateAttribute('catalog_product', 'apptha_hotel_email', 'frontend_class', 'validate-email');
$installer->updateAttribute('catalog_product', 'apptha_hotel_fax_no', 'frontend_class', 'validate-digits');
$installer->updateAttribute('catalog_product', 'apptha_hotel_website', 'frontend_class', 'validate-url');
//$installer->updateAttribute('catalog_product', 'apptha_hotel_check_in', 'frontend_class', 'validate-number');
//$installer->updateAttribute('catalog_product', 'apptha_hotel_check_out', 'frontend_class', 'validate-number');
$installer->updateAttribute('catalog_product', 'apptha_hotel_postalcode', 'frontend_class', 'validate-digits');
$installer->updateAttribute('catalog_product', 'apptha_hotel_order_key', 'frontend_class', 'validate-alphanum');
$installer->updateAttribute('catalog_product', 'apptha_hotel_contact_no', 'frontend_class', 'validate-number');


$installer->endSetup();