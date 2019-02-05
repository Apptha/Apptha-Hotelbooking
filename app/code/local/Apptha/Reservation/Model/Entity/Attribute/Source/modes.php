<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 class Apptha_Reservation_Model_Entity_Attribute_Source_Modes extends Mage_Eav_Model_Entity_Attribute_Source_Abstract{
   	
   
	const NIGHT = 'night';
	const PERSON = 'person';

    public function getAllOptions()
    {
    	
    	$options =  array(
			array('value'=>self::NIGHT, 'label'=>Mage::helper('booking')->__("Per Night")),
            array('value'=>self::PERSON, 'label'=>Mage::helper('booking')->__("Per Person")),
        );
        return $options;
        
    }	
	
	
	
}