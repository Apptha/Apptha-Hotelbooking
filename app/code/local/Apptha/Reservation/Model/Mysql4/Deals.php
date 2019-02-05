<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 class Apptha_Reservation_Model_Mysql4_Deals extends Mage_Core_Model_Mysql4_Abstract{
   	
   	protected function _construct(){
		$this->_init('reservation/hotel_deal', 'id');
	}
		
	public function deleteByEntityId($id, $storeId = null){
		$condition =   'entity_id='.intval($id);
		if(!is_null($storeId)){
			$condition .= ' AND store_id='.intval($storeId);
		}
		$this->_getWriteAdapter()->delete($this->getMainTable(), $condition);
		 
		 return $this;
	}
}