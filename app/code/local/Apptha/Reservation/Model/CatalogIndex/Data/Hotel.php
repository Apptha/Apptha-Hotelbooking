<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Model_CatalogIndex_Data_Hotel extends Mage_CatalogIndex_Model_Data_Abstract
{
    public function getTypeCode()
    {
        return Apptha_Booking_Model_Product_Type::TYPE_HOTEL_PRODUCT;
    }
}
