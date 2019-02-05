<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 class Apptha_Reservation_Model_Product_Backend_Deal extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract {
 	
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
     * @return Apptha_Booking_Model_Product_Backend_Excludedays
     */
    public function afterSave($object) {

        $generalStoreId = $object->getStoreId();

        $deals = $object->getData($this->getAttribute()->getName());
        //echo '<pre>';print_r($deals);
        $roomType = $this->_getProduct()->getData('apptha_hotel_room_type');
        if (!is_array($deals)) {
            return $this;
        }
        Mage::getResourceSingleton('reservation/deals')->deleteByEntityId($object->getId(), $generalStoreId);
        
        return $this;
    }
    
 }