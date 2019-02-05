<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Block_Adminhtml_Sales_Order_View_Details extends Mage_Adminhtml_Block_Sales_Order_Abstract
    implements Mage_Adminhtml_Block_Widget_Tab_Interface{
	
    public function getOrder()
    {
        return Mage::registry('current_order');
    }

    public function getTabLabel()
    {
        return Mage::helper('reservation')->__('Hotel Reservation');
    }

    public function getTabTitle()
    {
        return Mage::helper('reservation')->__('Hotel Booking Information');
    }

    public function canShowTab()
    {
        return true;
    }

	public function isHidden(){
		// Show only if there are reserved items in order
		return !$this->getItemsCollection()->getSize();
	}
	
	public function getItemsCollection(){
		if(!$this->_collection){
			$id = $this->getRequest()->getParam('order_id');
			$order = $this->getOrder();
			$this->_collection = Mage::getModel('reservation/orders')->getCollection()->addOrderIdFilter($order->getIncrementId())->load();
		}
		return $this->_collection;
	}
	
	public function getCollection(){
		return $this->getItemsCollection();
	}
	
}