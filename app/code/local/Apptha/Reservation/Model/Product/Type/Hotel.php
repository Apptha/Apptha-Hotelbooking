<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 class Apptha_Reservation_Model_Product_Type_Hotel extends Mage_Catalog_Model_Product_Type_Abstract
 {
 	const CKECK_IN_DATE_OPTION = 'apptha_check_in';
    const CKECK_OUT_DATE_OPTION = 'apptha_check_out';
    const CHECK_IN_ROOMS = 'apptha_check_in_rooms';
    const CHECK_IN_GUESTS = 'apptha_check_in_guests';

    protected $_isDuplicable = false;

 	public function getProduct($product = null) {
        if(!$product) {
            $product = $this->_product;
            if(!$product) $product = Mage::registry('product');
            if(!$product) {
                throw new Mage_Core_Exception("Can't retrieve product");
            }
            $product = Mage::getModel('catalog/product')->load($product->getId());
        }
        $this->setProduct($product);
        return $product;
    }
 	  
    public function prepareForCart(Varien_Object $buyRequest, $product = null) {

        if(!$product) $product = $this->getProduct();

        /* Adding custom options */
        if(!$product) $product = $this->getProduct();

        if($buyRequest->getAppthaCheckIn()) {
            
            if(!$buyRequest->getAppthaCheckOut()) {
                $buyRequest->setAppthaCheckOut($buyRequest->getAppthaCheckIn());
            }

            $checkIn = new Zend_Date($buyRequest->getAppthaCheckIn(), Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT));
            $checkOut = new Zend_Date($buyRequest->getAppthaCheckOut(), Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT));
            $rooms = $buyRequest->getAppthaCheckInRooms();
            $guests = $buyRequest->getAppthaCheckInGuests();

            $product->addCustomOption(self::CKECK_IN_DATE_OPTION, $checkIn->toString('Y-m-d', 'php'), $product);
            $product->addCustomOption(self::CKECK_OUT_DATE_OPTION,  $checkOut->toString('Y-m-d', 'php'), $product);
            $product->addCustomOption(self::FROM_TIME_OPTION_NAME,  $rooms, $product);
            $product->addCustomOption(self::CHECK_IN_ROOMS, $guests, $product);

            /* Check if product can be added to cart */
            $isRoomAvailable = $this->isRoomAvailable($product, $buyRequest->getQty(), true);

            if(!$isAvail) {
                Mage::throwException(
                    Mage::helper('reservation')->__("Only %s rooms available right now,Please select according to the availablity.", $itemsLeft)
                );
            }
            return parent::prepareForCart($buyRequest, $product);
        }
        return Mage::helper('reservation')->__('Please specify reservation information');

    }
    
    public function isRoomAvailable($product = null, $qty=1, $includeAvail = false){
    	
    }
    
    /**
     * Check is virtual product
     *
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     */
    public function isVirtual($product = null)
    {
        if(is_null($product)) {
            $product = Mage::registry('product');
        }
        $product->load($product->getId());
        $shippingActive = $product->getAppthaHotelShipping()?false : true;       
        
        return $shippingActive;
    }
    
        
    public function isExcludeDays($product){
    	$productId = $product->getId();
    	$collection = Mage::getModel('reservation/excludedays')->getCollection()
					->addEntityIdFilter($productId)
					->addStoreIdFilter($product->getStoreId())
					->getItems();
		$todayDate = new Zend_Date();
		$today = Apptha_Reservation_Helper_Data::toTimestamp(Apptha_Reservation_Helper_Data::currentDate());
		
		foreach ($collection as $_item):
			$periodFrom = Apptha_Reservation_Helper_Data::toTimestamp($_item->getPeriodFrom());
			$periodTo = Apptha_Reservation_Helper_Data::toTimestamp($_item->getPeriodTo());
			if($today >= $periodFrom && $today <= $periodTo){
				return false;
			}elseif($periodFrom == $today){
				return false;
			}elseif($periodTo == $today){
				return false;
			}
		endforeach;
		
		return true;
    	
    }
    
 	public function isPeriod($product){
 		$periodFrom = $product->getAppthaHotelPeriodFrom();
    	$periodTo = $product->getAppthaHotelPeriodTo();
    	
    	$todayDate = Apptha_Reservation_Helper_Data::currentDate();
    	$toDate = new Zend_Date($periodTo);
    	$fromDate = new Zend_Date($periodFrom);
		$today = Apptha_Reservation_Helper_Data::toTimestamp($todayDate);
		$periodFrom = Apptha_Reservation_Helper_Data::toTimestamp($periodFrom);
		$periodTo = Apptha_Reservation_Helper_Data::toTimestamp($periodTo);
 		if(($today >= $periodFrom && $today <= $periodTo)){
				return true;
			}else{
				return false;
			}
    }

    /**
     * Check is product available for sale
     *
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     */
    public function isSalable($product = null)
    {
        $salable = $this->getProduct($product)->getStatus() == Mage_Catalog_Model_Product_Status::STATUS_ENABLED;
        $exclude = $this->isExcludeDays($product);
        $period = $this->isPeriod($product);
        if ($salable && $period && $exclude) { 
            $salable = true;
        }else{
        	$salable = false;
        }

        return $salable;
    }
    
    
 }
?>
