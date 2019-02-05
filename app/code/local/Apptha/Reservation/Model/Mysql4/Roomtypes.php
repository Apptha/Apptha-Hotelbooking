<?php

/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Model_Mysql4_Roomtypes extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct() {
        $this->_init('reservation/room_types', 'id');
    }

    public function deleteByEntityId($id, $storeId = null) {
        $condition = 'entity_id=' . intval($id);
        if (!is_null($storeId)) {
            $condition .= ' AND store_id=' . intval($storeId);
        }
        $this->_getWriteAdapter()->delete($this->getMainTable(), $condition);

        return $this;
    }

    public function getHotelPrice($productId) {
        $query = "SELECT max(room_quantity) as rooms, max(room_capacity) as guests, min(room_price_per_night) as lowestprice, min(room_special_price) as lowspecialprice, count(*) as totalrooms FROM " . $this->getMainTable() . " WHERE entity_id=" . $productId . " GROUP BY entity_id";
        return $this->_getWriteAdapter()->fetchAll($query);
    }

    public function getHotelrooms($productId, $roomType) {
        $query = "SELECT room_quantity,room_capacity FROM " . $this->getMainTable() . " WHERE entity_id=" . $productId . " AND id=" . $roomType . " GROUP BY entity_id";
        return $this->_getWriteAdapter()->fetchRow($query);
    }

    
}