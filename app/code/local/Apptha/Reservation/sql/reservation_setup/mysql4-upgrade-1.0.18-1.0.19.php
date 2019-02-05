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

$installer->run("
	ALTER TABLE {$this->getTable('reservation/exclude_days')} ADD `period_day` VARCHAR (50) NULL;
	");

$installer->endSetup();