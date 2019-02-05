<?php

/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Model_Excludedays extends Mage_Core_Model_Abstract {
    /** Single day */
    const TYPE_SINGLE = 'single';
    /** Recurrent day of week, 0-7 */
    const TYPE_RECURRENT_DAY = 'recurrent_day';
    /** Single period */
    const TYPE_PERIOD = 'period';

    protected function _construct() {
        $this->_init('reservation/excludedays');
    }

    /**
     * Converts  to array
     * @return array 
     */
    public function toArray(array $arrAttributes = array()) {
       
        $From = new Zend_Date($this->getPeriodFrom(), AW_Core_Model_Abstract::DB_DATE_FORMAT);
        $To = new Zend_Date($this->getPeriodTo(), AW_Core_Model_Abstract::DB_DATE_FORMAT);
        switch ($this->getType()) {
            case self::TYPE_SINGLE:
                if ($this->getOutputFormat()) {
                    $From = $From->toString($this->getOutputFormat());
                }
                $out = $From;
                break;
            case self::TYPE_RECURRENT_DAY:
                $arr = $From->toArray();
                $weekday = $arr['weekday'] == 7 ? 0 : (int) $arr['weekday'];
                $out = $weekday;
                break;
            case self::TYPE_PERIOD:
                if ($this->getOutputFormat()) {
                    $From = $From->toString($this->getOutputFormat());
                    $To = $To->toString($this->getOutputFormat());
                }
                $out = (array('from' => $From, 'to' => $To));
                break;
        }
        return $out;
    }

    /**
     * Checks if date is available
     * @param Zend_Date $Date
     * @return boolean
     */
    public function isDateAvail(Zend_Date $Date) {
        $From = new Zend_Date($this->getPeriodFrom(), AW_Core_Model_Abstract::DB_DATE_FORMAT);
        $To = new Zend_Date($this->getPeriodTo(), AW_Core_Model_Abstract::DB_DATE_FORMAT);

        if ($this->getType() == self::TYPE_SINGLE) {
            return $Date->compare($From, Zend_Date::DATE_SHORT) != 0;
        }
        if ($this->getType() == self::TYPE_RECURRENT_DAY) {
            return $Date->compare($From, Zend_Date::WEEKDAY) != 0;
        }
        if ($this->getType() == self::TYPE_PERIOD) {
            return!(($Date->compare($From, Zend_Date::DATE_SHORT) >= 0 ) && ($Date->compare($To, Zend_Date::DATE_SHORT) <= 0 ));
        }
    }

    /**
     * Wrapper for getPeriodType()
     * @return string
     */
    public function getType() {
        return $this->getPeriodType();
    }

    /**
     * Sets output format. If no format specified, Zend_Date objects are returned
     * @return string
     */
    public function getOutputFormat() {
        if (!$this->getData('output_format')) {
            return false;
        }
        return $this->getData('output_format');
    }

    public function getExcludeDatesPeriodRes($arrExcludeDatesPeriod) {
 
        $arrExcludeDates = array();
        $arrDateMatch = array();
        
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

    public function getExcludeDatesRes($arrExcludeDatesSingle = null, $arrExcludeDatesPeriodRes) {
         
        $arrExcludeDates = array();
        $arrDateMatch = array();
        $arrExcludeDatesFinal = array();
        $values = '';
        $arrExcludeDatesFinal2 = '';
        foreach ($arrExcludeDatesSingle as $arrExcludeDateValue) {
            $arrExcludeDates[] = $arrExcludeDateValue->getPeriodFrom();
        }

        if (count($arrExcludeDates == 0)) {
            $arrExcludeDates1 = $arrExcludeDatesPeriodRes;
        }
        if (count($arrExcludeDates > 0)) {
            $arrExcludeDates1 = array_merge($arrExcludeDates, $arrExcludeDatesPeriodRes);
        }
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

    public function getavailabilityDatesRes($arrExcludeDatesSingle = null, $arrExcludeDatesPeriodRes) {

         $arrExcludeDates = array();
         $values = '';
         $arrExcludeDatesFinal = array();
         $arrExcludeDatesFinal2 = '';
        foreach ($arrExcludeDatesSingle as $arrExcludeDateValue) {
            $arrExcludeDates[] = $arrExcludeDateValue->getPeriodFrom();
        }

        if (count($arrExcludeDates == 0)) {
            $arrExcludeDates1 = $arrExcludeDatesPeriodRes;
        }
        if (count($arrExcludeDates > 0)) {
            $arrExcludeDates1 = array_merge($arrExcludeDates, $arrExcludeDatesPeriodRes);
        }
        foreach ($arrExcludeDates1 as $key => $values) {
            $arrExcludeDatesFinal[] = date('Y-n-j', strtotime($values));
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