<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Block_Checkout_Cart_Item_Renderer extends Mage_Checkout_Block_Cart_Item_Renderer
{
	
	
	public function _getRoomAvailability($id){
    	$roomTypeModel = Mage::getModel('reservation/roomtypes');
    	$roomCollection = $roomTypeModel->load($id);
    	return $roomCollection->getRoomQuantity();
    }
    
	protected function _getBookingOptions(){
		$product = $this->getProduct();
		
		$quoteItem = $this->getItem();
		
		$quoteItemId = $quoteItem->getQuote()->getId();
		$orderId = $quoteItem->getId();
		
		$hotelQuoteModel = Mage::getModel('reservation/orders')->getCollection();
		$hotelQuotes = $hotelQuoteModel->getQuoteOrderIdFilter($quoteItemId, $orderId)->load();
                
		foreach($hotelQuotes as $quotes){
                    
			$checkIn = $quotes->getPeriodFrom();
			$checkOut =$quotes->getPeriodTo();
			$rooms = $quotes->getRooms();
			$guests = $quotes->getGuests();
		        $type = $quotes->getRoomType();
			$typeId = $quotes->getRoomTypeId();
		}
		
		
		$data = array(
			new Zend_Date($checkIn),
			new Zend_Date($checkOut)
		);	
		$noOfDays = Apptha_Reservation_Helper_Data::getDays($checkIn, $checkOut);
		$isThisEnabled = Mage::getStoreConfig('advanced/modules_disable_output/Apptha_Reservation');
		if($isThisEnabled == 0){		
		return array(
			array('label' => $this->__('Room Type'), 'value' => $type),
			array('label' => $this->__('Check-In'), 'value' => $checkIn),
			array('label' => $this->__('Check-Out'), 'value' => $checkOut),
			array('label' => $this->__('Days'), 'value' => $noOfDays.' Night(s)'),			
			array('label' => $this->__('No of Rooms'), 'value' => $rooms),
			array('label' => $this->__('No of Guests'), 'value' => $guests)
		);
	}
}

    
    public function getOptionList(){
        return array_merge($this->_getBookingOptions(), parent::getOptionList());
    }
    public function getProductOptions()
    {
        /* @var $helper Mage_Catalog_Helper_Product_Configuration */
        $helper = Mage::helper('catalog/product_configuration');
        return $helper->getCustomOptions($this->getItem());
    }


//    public function getOptionList()
//    {
//        return $this->getProductOptions();
//    }
	public function getLoadedProduct()
    {
        return $this->getProduct()->load($this->getProduct()->getId());
    }
    
    

    
}