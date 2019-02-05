<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Block_Sales_Order_Items_Renderer_Default extends Mage_Sales_Block_Order_Item_Renderer_Default {
    
	public function getItemOptions() {
		$result = array();
                $orderId = $this->getRequest()->getParam('order_id'); 
                $roomName = '';
		if ($options = $this->getOrderItem()->getProductOptions()) {
					    
		
			$productOptions = $this->getOrderItem()->getProductOptions();
			foreach($productOptions as $options){
				$checkIn = $options['check-in'];
				$checkOut =$options['check-out'];
				$rooms = $options['rooms'];
				$guests = $options['guests'];

                                $hotelQuoteModel = Mage::getModel('reservation/orders')->getCollection()
                                        ->addFieldToFilter('order_item_id',array('eq' => $orderId))
                                        ->getFirstItem();
                                $roomName = $hotelQuoteModel->getRoomType();               
                                                   
                                    }
                              
			}
			
			$noOfDays = Apptha_Reservation_Helper_Data::getDays($checkIn, $checkOut);
			$result = array(    
                                array('label' => $this->__('Room Type'), 'value' => $roomName),
				array('label' => $this->__('Check In'), 'value' => $checkIn),
				array('label' => $this->__('Check Out'), 'value' => $checkOut),
				array('label' => $this->__('Days'), 'value' => $noOfDays.' Night(s)'),			
                                array('label' => $this->__('No of Rooms'), 'value' => $rooms),
				array('label' => $this->__('No of Guests'), 'value' => $guests)
			);
		
		return $result;
	}
}