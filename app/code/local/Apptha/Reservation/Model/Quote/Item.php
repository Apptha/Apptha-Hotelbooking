<?php

/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Model_Quote_Item extends Mage_Sales_Model_Quote_Item {

    public function setPrice($value) {

        $productType = $this->getProductType();
        if ($productType == Apptha_Reservation_Helper_Data::PRODUCT_TYPE_CODE) {
            $custom_price = Mage::app()->getRequest()->getParam('cust_price');

            if ($custom_price) {
                Mage::getSingleton('checkout/session')->setCustom($custom_price);
            }
            $strCustomPrice = Mage::getSingleton('checkout/session')->getCustom();
            
           
            $hotelPrice = Mage::getSingleton('core/session')->getBuyPrice();

            $this->setBaseCalculationPrice(null);
            $this->setConvertedPrice(null);
            if (($productType == Apptha_Reservation_Helper_Data::PRODUCT_TYPE_CODE)) {
                $quoteProductId = $this->getProduct()->getId();
                $quoteId = $this->getQuote()->getId();
                $quoteOrders = Apptha_Reservation_Helper_Data::_getHotelQuoteOrders($quoteProductId, $quoteId);
               
               
                foreach ($quoteOrders->getItems() as $item) {
                    $roomTypeId = $item->getRoomTypeId();
                    $col = Mage::getModel('reservation/roomtypes')->load($roomTypeId);
                    if($col['room_special_price']>0){
                        $hotelPrice = $col['room_special_price'];
                    }else{
                        $hotelPrice = $col['room_price_per_night'];
                    }
                    
                    
                 }
               
                $value = $hotelPrice;
                
              
            }
            $strCustomPrice = floor($strCustomPrice);
          
            return $this->setData('price', ($value + $strCustomPrice));
        } else {
            return $this->setData('price', $value);
        }
    }

    public function addQty($qty) {

        $session = Mage::getSingleton('core/session');
        $params = Mage::app()->getRequest()->getParams();

        $productType = $this->getProductType();
        if (($productType == Apptha_Reservation_Helper_Data::PRODUCT_TYPE_CODE)) {
            $quoteProductId = $this->getProduct()->getId();
            $quoteId = $this->getQuote()->getId();
            $quoteOrders = Apptha_Reservation_Helper_Data::_getHotelQuoteOrders($quoteProductId, $quoteId);

            $hotelQuoteModel = Mage::getResourceModel('reservation/orders')->selectByQuoteId($quoteProductId, $quoteId, $params['guests'],$params['roomtype']);

            $days = Apptha_Reservation_Helper_Data::getDays($params['check-in'], $params['check-out']);
            $qty = $days * $params['rooms'];
        }

        $qty = $this->_prepareQty($qty);

        /**
         * We can't modify quontity of existing items which have parent
         * This qty declared just once duering add process and is not editable
         */
        if (!$this->getParentItem() || !$this->getId()) {
            $this->setQtyToAdd($qty);
            $this->setQty($qty);
        }

        return $this;
    }

}