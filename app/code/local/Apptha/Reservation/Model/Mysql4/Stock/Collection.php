<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Model_Mysql4_Stock_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
 {
 
 	protected $_storeId = null;
	
	public function _construct(){
		parent::_construct();
		$this->_init('reservation/stock');
		if(!Mage::app()->isSingleStoreMode() && Mage::app()->getStore()->getId()){
			
			$this->setStoreId(Mage::app()->getStore()->getId());
		}
	}
	
 /**
	* Adds filter by entity_id (product_id)
	*
	* @param int $id entity id
	* @return Apptha_Booking_Model_Mysql4_Excludedays_Collection
	*/	
	public function addEntityIdFilter($id){
		$this->getSelect()
			->where('entity_id=?', $id);
		return $this;	
	}
	
	
 	public function getRoomPeriodIdFilter($id, $from, $productId, $orderId){
		 $this->getSelect()->where('room_id='.intval($id))
						->where('period_date_timestamp="'.intval($from).'"')
						->where('entity_id='.intval($productId))
						->where('order_id='.intval($orderId));
		return $this;
	}
	
 	public function getPeriodIdFilter($id, $from, $productId){
		 $this->getSelect()->where('room_id='.intval($id))
						->where('period_date_timestamp="'.intval($from).'"')
						->where('entity_id='.intval($productId));
		return $this;
	}
	
 	public function getHotelPeriodIdFilter($from, $productId){
		$this->getSelect()->where('is_rooms=0')
						->where('period_date_timestamp='.intval($from))
						->where('entity_id='.intval($productId));
		return $this;
	}
	
 		
	public function addStoreIdFilter($id){
		$this->getSelect()
			->where('store_id='.$id." OR store_id=0");
			
		return $this;	
	}	
	
	public function load($printQuery = false, $logQuery = false){
		$this->_beforeLoad();
		return  parent::load($printQuery, $logQuery);
    }	
    
    protected function _beforeLoad(){
		if(($this->_storeId)){
			$this->addStoreIdFilter($this->_storeId);
		}
		return $this;
	}
	
	public function setStoreId($id){
		$this->_storeId = $id;
		return $this;
	}
	
 		
 }