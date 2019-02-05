<?php

/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Block_Hotel_List extends Mage_Catalog_Block_Product_Abstract {

    /**
     * Default toolbar block name
     *
     * @var string
     */
    protected $_defaultToolbarBlock = 'catalog/product_list_toolbar';
    /**
     * Product Collection
     *
     * @var Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected $_productCollection;

    public function _productCollection() {
        $today = new Zend_Date();
        $today = $today->get(Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
        $prefix = Mage::getConfig()->getTablePrefix();
        $collectionHotel = Mage::getModel('catalog/product')->getCollection();
        
       

        $collectionHotel->addAttributeToFilter('type_id', array('eq' => 'hotel'))
                ->addAttributeToFilter('status', array('eq' => '1'))
                ->addAttributeToFilter('apptha_hotel_period_to', array('gteq' => "$today"));
         
        return $collectionHotel;
    }

    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getProductCollection() {
        $dir = "";
        $order = "";
        $productIds = array();
        $resultId = array();
        $productId = array();        
        if (is_null($this->_productCollection)) {
            $layer = $this->getLayer();
            //If city matches

            $today = new Zend_Date();
            $today = $today->get(Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
            $noSearch = false;
            $requestObj = Mage::app();
            $searchParams = $requestObj->getRequest()->getParams();
          
            //Session Start
            if (isset($searchParams['city'])) {
                Mage::getSingleton('core/session')->setCity($searchParams['city']);
            }else{
                 Mage::getSingleton('core/session')->unsCity();
            }
            if (isset($searchParams['check-in'])) {
                Mage::getSingleton('core/session')->setCheckIn($searchParams['check-in']);
            }else{
                 Mage::getSingleton('core/session')->unsCheckIn();
            }
            if (isset($searchParams['check-out'])) {
                Mage::getSingleton('core/session')->setCheckOutSearch($searchParams['check-out']);
            }else{
                 Mage::getSingleton('core/session')->unsCheckOutSearch();
            }
            //Session Ends
            $collectionHotel = $this->_productCollection();
            if (isset($searchParams['dir'])) {
                $dir = $searchParams['dir'];
            }
            if (isset($searchParams['order'])) {
                $order = $searchParams['order'];
              
            }
            

            if ($searchParams) {
                
                $collectionHotelCount = 0;
                $city = $requestObj->getRequest()->getParam('city');
                $checkIn = $requestObj->getRequest()->getParam('check-in');
                $checkOutSearch = $requestObj->getRequest()->getParam('check-out');

                if ($city) {
                    //If city matches

                    $collectionHotel->addAttributeToFilter('apptha_hotel_city', $city);
                    
                    $collectionHotel->addAttributeToSort($order, $dir);                   
                                       
                    $collectionHotelCount = $collectionHotel->count();
                    $noSearch = true;
                }
                  if (($checkIn != '') || ($checkOutSearch != '')) {
                      
                    
                    //If check in date matches
                    $collectionHotel->addAttributeToFilter('apptha_hotel_period_from', array('lteq' => $checkIn))
                            ->addAttributeToFilter('apptha_hotel_period_to', array('gteq' => $checkOutSearch));
                    
                    $collectionHotel->addAttributeToSort($order, $dir)->load();     
                    
                                       
                    
                    $collectionHotelCount = $collectionHotel->count();
                    $noSearch = true;
                }
                
               
                foreach($roomOrders as $ro)
                {
                  $userOrders =  $ro->getOrderId();
                }
                
                if($collectionHotelCount != 0){
                    
                    foreach($collectionHotel as $products){
                        
                        array_push($productIds,$products->getEntityId());
                       
                    }
                    
                   
                }
         
            } else {
                $collectionHotel = $this->_productCollection();
                    
                $collectionHotel->addAttributeToSort($order, $dir)->load();
                   
                $noSearch = true;
            }
            if (!$noSearch) {
                $collectionHotel = $this->_productCollection();
               // $collectionHotel->addAttributeToSort($order, $dir)->load();
                
            }
 
            $this->_productCollection = $collectionHotel;
        }

        return $this->_productCollection;
    }

    /**
     * Get catalog layer model
     *
     * @return Mage_Catalog_Model_Layer
     */
    public function getLayer() {
        $layer = Mage::registry('current_layer');
        if ($layer) {
            return $layer;
        }
        return Mage::getSingleton('catalog/layer');
    }

    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getLoadedProductCollection() {

        return $this->_getProductCollection();
    }

    /**
     * Retrieve current view mode
     *
     * @return string
     */
    public function getMode() {
        return $this->getChild('toolbar')->getCurrentMode();
    }

    /**
     * Need use as _prepareLayout - but problem in declaring collection from
     * another block (was problem with search result)
     */
    protected function _beforeToHtml() {
        /* $toolbar = $this->getLayout()->createBlock('catalog/product_list_toolbar', microtime());
          if ($toolbarTemplate = $this->getToolbarTemplate()) {
          $toolbar->setTemplate($toolbarTemplate);
          } */
        $toolbar = $this->getToolbarBlock();

        // called prepare sortable parameters
        $collection = $this->_getProductCollection();

        // use sortable parameters
        if ($orders = $this->getAvailableOrders()) {
            $toolbar->setAvailableOrders($orders);
        }
        if ($sort = $this->getSortBy()) {
            $toolbar->setDefaultOrder($sort);
        }
        if ($dir = $this->getDefaultDirection()) {
            $toolbar->setDefaultDirection($dir);
        }
        if ($modes = $this->getModes()) {
            $toolbar->setModes($modes);
        }

        // set collection to toolbar and apply sort
        $toolbar->setCollection($collection);

        $this->setChild('toolbar', $toolbar);
        Mage::dispatchEvent('catalog_block_product_list_collection', array(
            'collection' => $this->_getProductCollection()
        ));

        $this->_getProductCollection();
        Mage::getModel('review/review')->appendSummary($this->_getProductCollection());
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve Toolbar block
     *
     * @return Mage_Catalog_Block_Product_List_Toolbar
     */
    public function getToolbarBlock() {
        if ($blockName = $this->getToolbarBlockName()) {
            if ($block = $this->getLayout()->getBlock($blockName)) {
                return $block;
            }
        }
        $block = $this->getLayout()->createBlock($this->_defaultToolbarBlock, microtime());
        return $block;
    }

    /**
     * Retrieve additional blocks html
     *
     * @return string
     */
    public function getAdditionalHtml() {
        return $this->getChildHtml('additional');
    }

    /**
     * Retrieve list toolbar HTML
     *
     * @return string
     */
    public function getToolbarHtml() {
        return $this->getChildHtml('toolbar');
    }

    public function setCollection($collection) {
        $this->_productCollection = $collection;
        return $this;
    }

    public function addAttribute($code) {
        $this->_getProductCollection()->addAttributeToSelect($code);
        return $this;
    }

    public function getPriceBlockTemplate() {
        return $this->_getData('price_block_template');
    }

    /**
     * Retrieve Catalog Config object
     *
     * @return Mage_Catalog_Model_Config
     */
    protected function _getConfig() {
        return Mage::getSingleton('catalog/config');
    }

    /**
     * Prepare Sort By fields from Category Data
     *
     * @param Mage_Catalog_Model_Category $category
     * @return Mage_Catalog_Block_Product_List
     */
    public function prepareSortableFieldsByCategory($category) {
        if (!$this->getAvailableOrders()) {
            $this->setAvailableOrders($category->getAvailableSortByOptions());
        }
        $availableOrders = $this->getAvailableOrders();
        if (!$this->getSortBy()) {
            if ($categorySortBy = $category->getDefaultSortBy()) {
                if (!$availableOrders) {
                    $availableOrders = $this->_getConfig()->getAttributeUsedForSortByArray();
                }
                if (isset($availableOrders[$categorySortBy])) {
                    $this->setSortBy($categorySortBy);
                }
            }
        }

        return $this;
    }

    public function _getHotelRooms($productId, $storeId) {
        return Mage::getModel('reservation/roomtypes')->getCollection()
                ->addEntityIdFilter($productId)
                ->addStoreIdFilter($storeId)
                ->getItems();
    }

    public function isExcludeDays($product = null) {
        $productId = $product->getId();
        $collection = Mage::getModel('reservation/excludedays')->getCollection()
                ->addEntityIdFilter($productId)
                ->addStoreIdFilter($product->getStoreId())
                ->getItems();
        $today = Apptha_Reservation_Helper_Data::toTimestamp(Apptha_Reservation_Helper_Data::currentDate());

        foreach ($collection as $_item):
            $periodFrom = Apptha_Reservation_Helper_Data::toTimestamp($_item->getPeriodFrom());
            $periodTo = Apptha_Reservation_Helper_Data::toTimestamp($_item->getPeriodTo());
            if ($today >= $periodFrom && $today <= $periodTo) {
                return false;
            } elseif ($periodFrom == $today) {
                return false;
            } elseif ($periodTo == $today) {
                return false;
            }
        endforeach;

        return true;
    }

    public function isPeriod($product = null) {
        $periodFrom = $product->getAppthaHotelPeriodFrom();
        $periodTo = $product->getAppthaHotelPeriodTo();

        $today = Apptha_Reservation_Helper_Data::toTimestamp(Apptha_Reservation_Helper_Data::currentDate());
        $periodFrom = Apptha_Reservation_Helper_Data::toTimestamp($periodFrom);
        $periodTo = Apptha_Reservation_Helper_Data::toTimestamp($periodTo);
        if ($today >= $periodFrom && $today <= $periodTo) {
            return true;
        } else {
            return false;
        }
    }
    
   
    
    
   

}
