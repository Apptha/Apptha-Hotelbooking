<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 class Apptha_Reservation_Model_Product_Backend_Roomtypes extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract {
 	
 	/**
     * Returns current product from registry
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _getProduct() {
        return Mage::registry('product');
    }

    /**
     * Validate data
     *
     * @param   Mage_Catalog_Model_Product $object
     * @return  this
     */
    public function validate($object) {

        $periods = $object->getData($this->getAttribute()->getName());
        if (empty($periods)) {
            return $this;
        }


        return $this;
    }



    /**
     * After Save Attribute manipulation
     *
     * @param Mage_Catalog_Model_Product $object
     * @return Apptha_Booking_Model_Product_Backend_Roomtypes
     */
    public function afterSave($object) {
        $generalStoreId = $object->getStoreId();

        $types = $object->getData($this->getAttribute()->getName());
        if (!is_array($types)) {
            return $this;
        }
//echo '<pre>';print_r($types);die;
        
        foreach ($types as $k=>$type) {
            if(!is_numeric($k)) continue;            
            
            $storeId = @$type['use_default_value'] ? 0 : $object->getStoreId();
			
			if($type['delete'] != 1){
				$roomModel = Mage::getModel('reservation/roomtypes');
				//echo $type['room_type_id'];die;
				if($type['room_type_id'] != ''){
					//echo $type['room_period_from'];die;
					$roomFrom = date("Y-m-d", strtotime($type['room_period_from']));
					$roomTo = date("Y-m-d", strtotime($type['room_period_to']));
					$roomModel->load($type['room_type_id'])
							->setEntityId($this->_getProduct()->getId())
			                ->setStoreId($storeId)
			                ->setRoomType($type['room_type'])                
			                ->setInclusions($type['inclusions'])
			                ->setRoomQuantity($type['room_quantity'])
			                ->setRoomCapacity($type['room_capacity'])
			                ->setRoomPricePerNight($type['room_price_per_night'])
			                ->setRoomSpecialPrice($type['room_special_price'])
			                ->setRoomPeriodFrom($type['room_period_from'])  
			                ->setRoomPeriodTo($type['room_period_to'])           
			                ->save();
			                //echo '<pre>';print_r($roomModel);die;
				}else{
					if($type['room_type'] != '' && $type['room_price_per_night'] != 0){
						 $roomFrom = date("Y-m-d", strtotime($type['room_period_from']));
					     $roomTo = date("Y-m-d", strtotime($type['room_period_to']));
						 $roomModel->setEntityId($this->_getProduct()->getId())
			                ->setStoreId($storeId)
			                ->setRoomType($type['room_type'])                
			                ->setInclusions($type['inclusions'])
			                ->setRoomQuantity($type['room_quantity'])
			                ->setRoomCapacity($type['room_capacity'])
			                ->setRoomPricePerNight($type['room_price_per_night'])
			                ->setRoomSpecialPrice($type['room_special_price'])
			                ->setRoomPeriodFrom($type['room_period_from'])  
			                ->setRoomPeriodTo($type['room_period_to'])            
			                ->save();
			                //echo '<pre>';print_r($roomModel);die;
					}					
				}
                
			}else{
				Mage::getModel('reservation/roomtypes')->load($type['room_type_id'])
																	->delete();
			}
			
            
        }
        return $this;
    }
    
 }