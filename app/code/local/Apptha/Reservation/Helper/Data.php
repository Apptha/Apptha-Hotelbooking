<?php

/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Helper_Data extends Mage_Core_Helper_Abstract {

    const PRODUCT_TYPE_CODE = 'hotel';
    const ONE_DAY = 86400;

    public static function _getRoomType() {
        return Mage::getModel('reservation/roomtypes');
    }

    public static function _getHotelOrders() {
        return Mage::getModel('reservation/orders');
    }

    public static function _getHotelInventory() {
        return Mage::getModel('reservation/inventory');
    }

    public static function _getHotelStock() {
        return Mage::getModel('reservation/stock');
    }

    public static function _getSession() {
        return Mage::getSingleton('core/session');
    }

    public static function toTimestamp($d = null) {
        if ($d) {
            $dateTimestamp = strtotime($d);
            return $dateTimestamp;
        } else {
            return 0;
        }
    }

    public static function strToDate($d) {
        $date = new Zend_Date($d);
        return $date;
    }

    public static function dateFormat($d) {
        return $d->get(Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    }

    public static function parseDate($strDate) {
        $date = strtotime($strDate);

        return array(
            'month' => date("m", $date),
            'day' => date("d", $date),
            'year' => date("Y", $date)
        );
    }

    public static function currentDate() {
        return Mage::app()->getLocale()->date()->toString(Varien_Date::DATE_INTERNAL_FORMAT);
    }

    public static function createDays($date1, $date2) {
        $array = array();
        $days = "";
        $strDate1 = mktime(0, 0, 0, $date1['month'], $date1['day'], $date1['year']);
        $strDate2 = mktime(0, 0, 0, $date2['month'], $date2['day'], $date2['year']);

        if ($strDate1 <= $strDate2) {
            $days = (int) ($strDate2 - $strDate1) / self::ONE_DAY;
        }
        return ($days != 0) ? $days : ($days + 1);
    }

    public static function getDays($date1, $date2) {
        $date1 = self::parseDate($date1);
        $date2 = self::parseDate($date2);
        $days = self::createDays($date1, $date2);
        return $days;
    }

    public static function checkIsSpecial($from, $to) {
        $today = new Zend_Date();
        $today = Apptha_Reservation_Helper_Data::toTimestamp($today->get(Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY));
        $from = Apptha_Reservation_Helper_Data::toTimestamp($from);
        $to = Apptha_Reservation_Helper_Data::toTimestamp($to);
        if (($today >= $from) && ($today <= $to)) {
            return true;
        } else {
            return false;
        }
    }

    public static function _getHotelQuoteOrders($quoteProductId, $quoteId) {
        $ordersModel = self::_getHotelOrders()->getCollection();
        return $ordersModel->getQuoteProductIdFilter($quoteId, $quoteProductId)->load();
    }

    public static function _getHotelCartItemEditUrl() {
        $url = Mage::getBaseUrl() . 'reservation/hotel/view/';
        return $url;
    }

    public static function dateDiff($start, $end) {
        $start_ts = strtotime($start);
        $end_ts = strtotime($end);
        $diff = $end_ts - $start_ts;
        round($diff / 86400);
        return round($diff / 86400);
    }

    function array_to_scv($array, $header_row = true, $col_sep = ",", $row_sep = "\n", $qut = '"') {
        if (!is_array($array) or !is_array($array[0]))
            return false;

        //Header row.
        if ($header_row) {
            foreach ($array[0] as $key => $val) {
                //Escaping quotes.
                $key = str_replace($qut, "$qut$qut", $key);
                $output .= "$col_sep$qut$key$qut";
            }
            $output = substr($output, 1) . "\n";
        }
        //Data rows.
        foreach ($array as $key => $val) {
            $tmp = '';
            foreach ($val as $cell_key => $cell_val) {
                //Escaping quotes.
                $cell_val = str_replace($qut, "$qut$qut", $cell_val);
                $tmp .= "$col_sep$qut$cell_val$qut";
            }
            $output .= substr($tmp, 1) . $row_sep;
        }

        return $output;
    }

    function exportCSV($data, $col_headers = array(), $return_string = false) {
        $stream = ($return_string) ? fopen('php://temp/maxmemory', 'w+') : fopen('php://output', 'w');

        if (!empty($col_headers)) {
            fputcsv($stream, $col_headers);
        }

        foreach ($data as $record) {
            fputcsv($stream, $record);
        }

        if ($return_string) {
            rewind($stream);
            $retVal = stream_get_contents($stream);
            fclose($stream);
            return $retVal;
        } else {
            fclose($stream);
        }
    }
    public function domainKey($tkey) {
        $message = "EM-HRMP0EFIL9XEV8YZAL7KCIUQ6NI5OREH4TSEB3TSRIF2SI1ROTAIDALG-JW";

        for ($i = 0; $i < strlen($tkey); $i++) {
            $key_array[] = $tkey[$i];
        }
        $enc_message = "";
        $kPos = 0;
        $chars_str = "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
        for ($i = 0; $i < strlen($chars_str); $i++) {
            $chars_array[] = $chars_str[$i];
        }
        for ($i = 0; $i < strlen($message); $i++) {
            $char = substr($message, $i, 1);

            $offset = $this->getOffset($key_array[$kPos], $char);
            $enc_message .= $chars_array[$offset];
            $kPos++;
            if ($kPos >= count($key_array)) {
                $kPos = 0;
            }
        }

        return $enc_message;
    }

    public function getOffset($start, $end) {

        $chars_str = "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
        for ($i = 0; $i < strlen($chars_str); $i++) {
            $chars_array[] = $chars_str[$i];
        }

        for ($i = count($chars_array) - 1; $i >= 0; $i--) {
            $lookupObj[ord($chars_array[$i])] = $i;
        }

        $sNum = $lookupObj[ord($start)];
        $eNum = $lookupObj[ord($end)];

        $offset = $eNum - $sNum;

        if ($offset < 0) {
            $offset = count($chars_array) + ($offset);
        }

        return $offset;
    }
}

?>
