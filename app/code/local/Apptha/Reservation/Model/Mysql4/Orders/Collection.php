<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Model_Mysql4_Orders_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
 {
 
 	protected $_storeId = null;
	
	public function _construct(){
		parent::_construct();
		$this->_init('reservation/orders');
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
	
	
 	/**
	 * Adds filter by quote id
	 * @param   int $id quote_it
	 * @return  Apptha_Booking_Model_Mysql4_Orders_Collection
	 */
	public function getQuoteIdFilter($id){
		$this->getSelect()->where('quote_id='.intval($id).' or !quote_id');
		return $this;
	}
	
 	/**
	 * Adds filter by quote id & Order id
	 * @param   int $id quote_it
	 * @return  Apptha_Booking_Model_Mysql4_Orders_Collection
	 */
	public function getQuoteOrderIdFilter($quoteid, $orderId){
		$this->getSelect()->where('quote_id='.intval($quoteid))
						->where('order_id='.intval($orderId));						
						
		return $this;
	}

	public function getQuoteProductIdFilter($quoteid, $productId){
		$this->getSelect()->where('quote_id='.intval($quoteid))
						->where('product_id='.intval($productId));

		return $this;
	}
	
 	public function getQuoteOrderProductIdFilter($orderid, $productId){
		$this->getSelect()->where('order_id='.intval($orderid))
						->where('product_id='.intval($productId));

		return $this;
	}
	
 	public function getSalesOrderIdFilter($orderId){
               
		//$this->getSelect()->where('order_item_id='.intval($quoteid))
						$this->getSelect()->where('order_id='.intval($orderId));						
						
		return $this;
	}
	
 public function getSalesIncrementIdFilter($orderId){
		$this->getSelect()->where('increment_id='.intval($orderId))
						;						
						
		return $this;
	}
	
	/**
	* Adds filter by store_id 
	*
	* @param int $id store id
	* @return Apptha_Booking_Model_Mysql4_Orders_Collection
	*/		
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
	
 public function addOrderIdFilter(){
		$this->getSelect()
			->where('increment_id!=?', '0');
		return $this;	
	}
	
 		
 }