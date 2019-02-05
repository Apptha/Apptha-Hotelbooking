<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Block_Adminhtml_Sales_Items_Column_Name extends Mage_Adminhtml_Block_Sales_Items_Column_Name{
    public function getOrderOptions(){
        $result = array();
        if ($options = $this->getItem()->getProductOptions()) {
		    
		    $quoteItem = $this->getItem();
		    
			$quoteItemId = $quoteItem->getId();
			$orderId = $quoteItem->getQuoteItemId();
			
			$hotelQuoteModel = Mage::getModel('reservation/orders')->getCollection();
			$hotelQuotes = $hotelQuoteModel->getSalesOrderIdFilter($orderId)->load();
                        if(count($hotelQuotes)!= 0){
			foreach($hotelQuotes as $quotes){
                            
				$checkIn = $quotes->getPeriodFrom();
				$checkOut =$quotes->getPeriodTo();
				$rooms = $quotes->getRooms();
				$guests = $quotes->getGuests();
                $roomName = $quotes->getRoomType();
			}
			$productOptions = $this->getItem()->getProductOptions();
			//print_r($productOptions);die;
			/*foreach($productOptions as $options){
				$checkIn = $options['check-in'];
				$checkOut =$options['check-out'];
				$rooms = $options['rooms'];
				$guests = $options['guests'];
				$roomtype = $options['room_type'];
	            break;
			} */
			
			$data = array(
				new Zend_Date($checkIn),
				new Zend_Date($checkOut)
			);
			$noOfDays = Apptha_Reservation_Helper_Data::getDays($checkIn, $checkOut);
					
			$result = array(
				array('label' => $this->__('Room Name :'.$roomName)),
                array('label' => $this->__('Check In :'.$checkIn)),
				array('label' => $this->__('Check Out :'.$checkOut)),
				array('label' => $this->__('Days :'.$noOfDays.' Night(s)')),			
                array('label' => $this->__('No of Rooms(Qty) :'.$rooms)),
				array('label' => $this->__('No of Guests :'.$guests)),
			);

			$result = array_merge($result, parent::getOrderOptions());
        }
        return $result;
        }
    }
}
