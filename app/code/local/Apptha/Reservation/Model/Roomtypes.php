<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 class Apptha_Reservation_Model_Roomtypes extends Mage_Core_Model_Abstract{
   	
   	
	protected function _construct(){
		$this->_init('reservation/roomtypes');
	}
		
	/**
	 * Converts  to array
	 * @return array 
	 */
	public function toArray(array $arrAttributes = array()){
		
		$From = new Zend_Date($this->getPeriodFrom(), AW_Core_Model_Abstract::DB_DATE_FORMAT);
		$To = new Zend_Date($this->getPeriodTo(), AW_Core_Model_Abstract::DB_DATE_FORMAT);
		switch($this->getType()){
			case self::TYPE_SINGLE:
				if($this->getOutputFormat()){
					$From = $From->toString($this->getOutputFormat());
				}
				$out = $From;
			break;	
			case self::TYPE_RECURRENT_DAY:
				$arr = $From->toArray();
				$weekday = $arr['weekday'] == 7 ? 0 : (int)$arr['weekday'] ;
				$out = $weekday;
			break;
			case self::TYPE_PERIOD:
				if($this->getOutputFormat()){
					$From = $From->toString($this->getOutputFormat());
					$To = $To->toString($this->getOutputFormat());
				}
				$out =(array('from' => $From, 'to' => $To));
			break;
		}
		return $out;
	}
	
}