<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 class Apptha_Reservation_Model_Mysql4_Orders extends Mage_Core_Model_Mysql4_Abstract{
   	
   	protected function _construct(){
		$this->_init('reservation/hotel_orders', 'id');
	}
		
	public function deleteByQuoteId($id, $quote_id, $storeId = null){
		$condition =   'order_id='.intval($id);
		$condition .=   ' AND quote_id='.intval($quote_id);
		if(!is_null($storeId)){
			$condition .= ' AND store_id='.intval($storeId);
		}
		$this->_getWriteAdapter()->delete($this->getMainTable(), $condition);
                
		return $this;
	}
	
 	public function deleteHotelQuoteItem(Mage_Sales_Model_Quote_Item $QuoteItem) {
        $condition =   "order_id=".intval($QuoteItem->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $condition);
        return $this;
    }
    
 	public function deleteHotelOrderIncrementId($id) {
        $condition =   "increment_id=".$id;
        $this->_getWriteAdapter()->delete($this->getMainTable(), $condition);
        return $this;
    }
    
    public function updateHotelOrder($data, $quoteOrderId){
    	
    	$dbWriteAdapter = $this->_getWriteAdapter();
    	
    	$incrementId = $data['increment'];
    	$orderIncrementId = $data['order_item_id'];
    	$sqlUpdate = "update ".$this->getMainTable()." set order_item_id=".$orderIncrementId.", increment_id=".$incrementId." where order_id=".$quoteOrderId;
        $dbWriteAdapter->query($sqlUpdate);
    	return $this;
    }
    public function selectByQuoteId($id, $quote_id, $guest,$roomType){
        
        $roomTypeCollection= Mage::getModel('reservation/roomtypes')->getCollection()
                             ->addFieldToFilter('id',array('eq'=>$roomType));
        $roomTypeName = $roomTypeCollection->getFirstItem()->getRoomType();
   
        $storeId = Mage::app()->getStore()->getStoreId();
    	$dbWriteAdapter = $this->_getWriteAdapter();
    	$condition =   'product_id='.intval($id);
		$condition .=   ' AND quote_id='.intval($quote_id);
		if(!is_null($storeId)){
			$condition .= ' AND store_id='.intval($storeId);
		}	
    	
    	$sqlUpdate = "update ".$this->getMainTable()." set guests=".$guest.",room_type_id=".$roomType.",room_type='".$roomTypeName."' where ".$condition;
       
        $dbWriteAdapter->query($sqlUpdate);
    	return $this;
    }
}