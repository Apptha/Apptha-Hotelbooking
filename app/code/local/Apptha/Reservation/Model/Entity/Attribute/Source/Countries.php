<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 class Apptha_Reservation_Model_Entity_Attribute_Source_Countries extends Mage_Eav_Model_Entity_Attribute_Source_Abstract{
   	
   
	const NIGHT = 'night';
	const PERSON = 'person';

    public function getAllOptions()
    {
    	$countryArray = array();
    	//$countryCollection = Mage::getModel('directory/country_api')->items();
    	$countryCollection = Mage::getResourceModel('directory/country_collection')->loadData()->toOptionArray(false);
    	$i = 0;
    	foreach($countryCollection as $key => $country){
    		$countryArray[$i]['value'] = $country['value'];
    		$countryArray[$i]['label'] = $country['label'];
    		$i++;
    	}
        return $countryArray;
        
    }	
	
	
	
}