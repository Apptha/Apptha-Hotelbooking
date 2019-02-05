<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Block_Hotel_Search extends Mage_Core_Block_Template
{
	protected function _prepareLayout()
    {
    	
    }
    
    public function getCountries()
    {
    	$countryArray = array();
    	//$countryCollection = Mage::getModel('directory/country_api')->items();
    	$countryCollection = Mage::getResourceModel('directory/country_collection')->loadData()->toOptionArray(false);
        return $countryCollection;
        
    }

    public function getCities()
    {
    	$cityArray = array();
    	$attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', 'apptha_hotel_city');
		$cityArray = $attribute->getSource()->getAllOptions(true, true);
    	
        return $cityArray;
        
    }	
}