<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Model_Inventory extends Mage_Core_Model_Abstract{
	
	protected function _construct(){
		$this->_init('reservation/inventory');
	}
}