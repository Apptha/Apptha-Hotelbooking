<?php

class Apptha_Reservation_Block_Hotel_Hotelbanner extends Mage_Core_Block_Template {

    public function bannerslider() {

        $todayStartOfDayDate = Mage::app()->getLocale()->date()
                ->setTime('00:00:00')
                ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $todayEndOfDayDate = Mage::app()->getLocale()->date()
                ->setTime('23:59:59')
                ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $collectionHotel = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToFilter('status', array('eq' => '1'))
                //->addAttributeToFilter('apptha_hotel_period_to', array('gteq' => date('Y-m-d', strtotime($todayStartOfDayDate))));
               ->addAttributeToFilter('apptha_hotel_period_from', array('or' => array(
                       0 => array('date' => true, 'to' => $todayEndOfDayDate),
                        1 => array('is' => new Zend_Db_Expr('null')))
                       ), 'left')
               ->addAttributeToFilter('apptha_hotel_period_to', array('or' => array(
                        0 => array('date' => true, 'from' => $todayStartOfDayDate),
                     1 => array('is' => new Zend_Db_Expr('null')))
                       ), 'left')  
                 ->addAttributeToSelect('name')
                 ->addAttributeToSelect('description')
                 ->addAttributeToSelect('short_description')
                 ->addAttributeToFilter('banner',array('neq'=>'no_selection'));              
         
        return $collectionHotel;
    }

    public function popularhotels(){
        $storeId    = Mage::app()->getStore()->getId();
        $products = Mage::getResourceModel('reports/product_collection')
        ->addAttributeToSelect('*')
        ->setStoreId($storeId)
        ->addStoreFilter($storeId)
        ->addViewsCount()->setPageSize(1);     
        return $products;
        
    }
}

?>
