<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 class Apptha_Reservation_Model_Mysql4_Stock extends Mage_Core_Model_Mysql4_Abstract{
   	
   	protected function _construct(){
		$this->_init('reservation/hotel_stock', 'id');
	}
		
 	public function updateStock($id, $data, $dateTimeStamp, $productId = null, $storeId = null){
		$dbWriteAdapter = $this->_getWriteAdapter();
    	if($storeId){
    		$condition = " where period_date_timestamp=".$dateTimeStamp." and entity_id=".$productId." and room_id=".$id." and store_id=".$storeId;
    	}else{
    		$condition = " where period_date_timestamp=".$dateTimeStamp." and entity_id=".$productId." and room_id=".$id;
    	}
    	$rooms = $data['rooms'];
    	$isRooms = $data['is_rooms'];
    	$sqlUpdate = "update ".$this->getMainTable()." set rooms_available=".$rooms.",is_rooms=".$isRooms.$condition;
        $dbWriteAdapter->query($sqlUpdate);
    	return $this;
	}
	
 	public function updateCancelStock($id,$quote_id,$room_id,$rooms,$storeId = null ){
		$dbWriteAdapter = $this->_getWriteAdapter();
    	if($storeId){
    		$condition = " where order_id=".$id." and quote_id=".$quote_id." and room_id=".$room_id." and store_id=".$storeId;
    	}else{
    		$condition = " where order_id=".$id." and quote_id=".$quote_id." and room_id=".$room_id;
    	}
    
    	$sqlUpdate = "update ".$this->getMainTable()." set rooms_available=(rooms_available+".$rooms.")";
        $dbWriteAdapter->query($sqlUpdate);
    	return $this;
	}
	
 	public function insertStock($data, $storeId = null){
		$dbWriteAdapter = $this->_getWriteAdapter();
    	
		$rooms = $data['rooms_available'];
    	$roomId = $data['room_id'];
    	$productId = $data['product_id'];
    	$isRooms = $data['is_rooms'];
    	$date = $data['period_date'];
    	$dateTimeStamp = Apptha_Reservation_Helper_Data::toTimestamp($date);
    	$sqlUpdate = "insert into ".$this->getMainTable()." (entity_id,store_id,is_rooms,room_id,rooms_available,period_date,period_date_timestamp) values ($productId,$storeId,$isRooms,$roomId,$rooms,$date,$dateTimeStamp)";
        $dbWriteAdapter->query($sqlUpdate);
    	return $this;
	}
}