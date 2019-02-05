<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 class Apptha_Reservation_Model_Mysql4_Inventory extends Mage_Core_Model_Mysql4_Abstract{
   	
   	protected function _construct(){
		$this->_init('reservation/hotel_inventory', 'id');
	}
		
	public function updateStock($id, $data, $dateTimeStamp, $productId = null, $quoteId, $storeId = null){
		$dbWriteAdapter = $this->_getWriteAdapter();
    	if($storeId){
    		$condition = " where quote_id=".$quoteId." and booked_date=".$dateTimeStamp." and entity_id=".$productId." and room_id=".$id." and store_id=".$storeId;
    	}else{
    		$condition = " where quote_id=".$quoteId." and booked_date=".$dateTimeStamp." and entity_id=".$productId." and room_id=".$id;
    	}
    	$rooms = $data['rooms'];
    	$guests = $data['guests'];
    	$sqlUpdate = "update ".$this->getMainTable()." set rooms=".$rooms.", guests=".$guests.$condition;
        $dbWriteAdapter->query($sqlUpdate);
    	return $this;
	}
}