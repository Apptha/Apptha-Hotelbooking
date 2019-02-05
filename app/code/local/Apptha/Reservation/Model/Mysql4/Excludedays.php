<?php

/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Model_Mysql4_Excludedays extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct() {
        $this->_init('reservation/exclude_days', 'id');
    }

    public function deleteByEntityId($id, $storeId = null) {
        $condition = 'entity_id=' . intval($id);
        if (!is_null($storeId)) {
            $condition .= ' AND store_id=' . intval($storeId);
        }
        $this->_getWriteAdapter()->delete($this->getMainTable(), $condition);

        return $this;
    }

    public function getExcludeDatesPeriodRes($arrExcludeDatesPeriod) {
        foreach ($arrExcludeDatesPeriod as $arrExcludeDateValue) {
            $arrExcludeDates[] = $arrExcludeDateValue->getPeriodFrom();
            $arrExcludeDates[] = $arrExcludeDateValue->getPeriodTo();
        }
        for ($i = 0; $i < count($arrExcludeDates); $i++) {
            $strFromDate = strtotime($arrExcludeDates[$i]);
            $i = $i + 1;
            $strToDate = strtotime($arrExcludeDates[$i]);
            while ($strFromDate <= $strToDate) {
                $arrDateMatch[] = date('Y-m-d', $strFromDate);
                $strFromDate = strtotime(date('Y-m-d', $strFromDate) . ' +1 day');
            }
        }
        return $arrDateMatch;
    }

    public function getExcludeDatesRes($arrExcludeDatesSingle, $arrExcludeDatesPeriodRes) {
        foreach ($arrExcludeDatesSingle as $arrExcludeDateValue) {
            $arrExcludeDates[] = $arrExcludeDateValue->getPeriodFrom();
        }
        
        $arrExcludeDates1 = array_merge($arrExcludeDates, $arrExcludeDatesPeriodRes);
        foreach ($arrExcludeDates1 as $key => $values) {
            $arrExcludeDatesFinal[] = date('n-j-Y', strtotime($values));
        }
        $count = count($arrExcludeDatesFinal);
        foreach ($arrExcludeDatesFinal as $key => $values) {
            if (($count - 1) == $key) {
                $arrExcludeDatesFinal2 .= '"' . $values . '"';
            }else
                $arrExcludeDatesFinal2 .= '"' . $values . '",';
        }
        return $arrExcludeDatesFinal2;
    }

}