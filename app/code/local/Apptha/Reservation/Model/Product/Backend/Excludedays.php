<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 class Apptha_Reservation_Model_Product_Backend_Excludedays extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract {
 	
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

        $periods = $object->getData($this->getAttribute()->getName());
        if (!is_array($periods)) {
            return $this;
        }

        Mage::getResourceSingleton('reservation/excludedays')->deleteByEntityId($object->getId(), $generalStoreId);

        foreach ($periods as $k=>$period) {
            if(!is_numeric($k)) continue;



            /* Preprocess period */
            if(is_numeric($period['period_from']) && $period['period_from']) {
                if($period['period_type'] == 'recurrent_date') {
                    $period['period_from'] = date('m/'.$period['period_from'].'/Y');
                }
            }

            if($period['period_type'] == 'recurrent_day') {
                $dow = $period['recurrent_day'];

                $Date = new Zend_Date;
                Zend_Date::setOptions(array('extend_month' => true)); // Fix Zend_Date::addMonth unexpected result


                while(Apptha_Reservation_Helper_Data::getDayOfWeek($Date) != $dow) {

                    $Date->addDayOfYear(1);
                }

                $period['period_from'] = $Date->toString(Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT));

            }

            if (
            !empty($period['delete']) ||

                empty($period['period_type']) ||
                empty($period['period_from']) ||
                !Apptha_Reservation_Helper_Data::toTimestamp($period['period_from']) ||
                (
                $period['period_type'] == 'period' &&
                    empty($period['period_to'])
                ) ||
                (
                $period['period_type'] == 'period' &&
                    (Apptha_Reservation_Helper_Data::toTimestamp($period['period_from']) >= Apptha_Reservation_Helper_Data::toTimestamp($period['period_to']))
            )
            ) {
                continue;
            }

            if(!is_numeric($k)) continue;

            $period['period_from'] = date('Y-m-d H:i:s', Apptha_Reservation_Helper_Data::toTimestamp($period['period_from']));
            $period['period_to'] = date('Y-m-d H:i:s', Apptha_Reservation_Helper_Data::toTimestamp($period['period_to']));



            $storeId = @$period['use_default_value'] ? 0 : $object->getStoreId();

            $ex = Mage::getModel('reservation/excludedays')
                ->setEntityId($this->_getProduct()->getId())
                ->setStoreId($storeId)
                ->setPeriodType($period['period_type'])
                ->setPeriodFrom($period['period_from'])
                ->setPeriodTo($period['period_to'])
                ->setPeriodDay($dow)
                ->save();
        }
        return $this;
    }
    
 }