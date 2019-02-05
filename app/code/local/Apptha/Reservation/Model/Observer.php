<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 class Apptha_Reservation_Model_Observer
 {
 	protected static $_urlRewrite;
 	const XML_PATH_ORDER_TEMPLATE = 'reservation/order/template';
 	const XML_PATH_EMAIL_RECIPIENT = 'contacts/email/recipient_email';
 	const XML_PATH_EMAIL_SENDER = '';
 	
    public function __construct() {
        
    }
    
    public function _getRoomType(){
    	return Mage::getModel('reservation/roomtypes');
    }
    
 	public function _getHotelOrders(){
    	return Mage::getModel('reservation/orders');
    }
    
 	public function _getHotelInventory(){
    	return Mage::getModel('reservation/inventory');
    }
    
 	public function _getHotelStock(){
    	return Mage::getModel('reservation/stock');
    }
    
    public function _getSession(){
    	return Mage::getSingleton('core/session');
    }
    
 	public function attachExcludeEditor($observer) {
 		
        $form = $observer->getForm();
        if ($excludedDays = $form->getElement('apptha_hotel_exclude_days')) {
            $excludedDays->setRenderer(
                Mage::getSingleton('core/layout')->createBlock('reservation/adminhtml_catalog_product_edit_tab_rules_exclude')
            );
        }
    }
    
    public function attachRoomTypeEditor($observer) {
        $form = $observer->getForm();
        if ($roomtypes = $form->getElement('apptha_hotel_room_type')) {
            $roomtypes->setRenderer(
                Mage::getSingleton('core/layout')->createBlock('reservation/adminhtml_catalog_product_edit_tab_rules_roomtype')
            );
        }
    }
    
    public function attachDealEditor($observer) {
        $form = $observer->getForm();
        if ($deals = $form->getElement('apptha_hotel_deal_from')) {
            $deals->setRenderer(
                Mage::getSingleton('core/layout')->createBlock('reservation/adminhtml_catalog_product_edit_tab_deals_deal')
            );
        }
    }
    
    /* Refund credit memo for customer's order - starts  */
    public function catalogProductRefundAfter(Varien_Event_Observer $observer){

        $creditmemo         = $observer->getEvent()->getCreditmemo()->getAllItems();
        $product_info       = $creditmemo[0]->getOrderItem()->getData();
       
        $item_id            = $product_info['item_id'];
        $order_id           = $product_info['order_id'];
        $store_id           = $product_info['store_id'];
        $product_type       = $product_info['product_type'];
        $product_options    = $product_info['product_options'];
        
        if($product_info['product_type']  == 'hotel'){

                $options        = unserialize($product_options);
                $check_in_date  = $options['info_buyRequest']['check-in'];
                $check_out_date = $options['info_buyRequest']['check-out'];

               
               $delete_refund_data = Mage::getModel('reservation/stock')->getCollection()
                                      ->addFieldToFilter('order_id',$order_id);


                foreach($delete_refund_data as $value)
                {
                   
                    $ss = Mage::getModel('reservation/stock')->setId($value->getId())->delete();
                  

                }
                           

        }
      
      
    }
    /* Refund credit memo for customer's order - ends  */
 
    
    public function catalogProductSaveAfter($observer){
        
    	$saveObject = $observer->getEvent()->getProduct();

        $_product = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('url_key',  $saveObject->getUrlKey());
       
        $size = $_product->getSize()+1;
        /* Dupicated product's url Modification */         
        if($saveObject->getIsDuplicate()==1){  
            $product = $this->_getRoomType()->getCollection()->addFieldToFilter('entity_id',  $saveObject->getOriginalId());
          
            
            $requestPaths = $saveObject->getUrlKey().$size;
            $requestPath = $requestPaths.'.html';
           
            
            foreach($product as $pro):
            
              $saveRooms = $this->_getRoomType()->setEntityId($saveObject->getEntityId())
    				->setStoreId(Mage::app()->getStore()->getId())
    				->setRoomType($pro->getRoomType())
    				
    				->setRoomPricePerPerson($pro->getRoomPricePerPerson())
    				->setInclusions($pro->getInclusions())
    				->setRoomQuantity($pro->getRoomQuantity())
    				->setRoomCapacity($pro->getRoomCapacity())
    				->setRoomPricePerNight($pro->getRoomPricePerNight())
    				->setRoomPricePerExtraPerson($pro->getRoomPricePerExtraPerson())
                                ->setRoomForDeal($pro->getRoomForDeal())
                                ->setRoomSpecialPrice($pro->getRoomSpecialPrice());
    				try{
                                    $saveRooms->save();
                                   
                                }catch(Exception $e){
                                    echo 'product not created successfully';
                                }
            
            endforeach;
         
         
        }else{
            $requestPath = $saveObject->getUrlKey().'.html';
        }
        
    	$idPath = 'product/'.$saveObject->getEntityId();
    	$productId = $saveObject->getEntityId();
    	$storeId    = $saveObject->getStoreId();
    	$productType = $saveObject->getTypeId();  
    	$rewrite = Mage::getModel('core/url_rewrite');
    	$rewrite->loadByIdPath($idPath);
    	$targetPath = 'reservation/hotel/view/id/'.$saveObject->getEntityId();
    	$rewriteData = array();
    	$resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');
        $write = $resource->getConnection('core_write');
        
        
        
        $tprefix = (string)Mage::getConfig()->getTablePrefix();
        
        $write->query("update ".$tprefix."catalog_product_index_price set min_price = '".$price."' where entity_id='".$productId."'");
        
       
        
        if($productType == Apptha_Reservation_Helper_Data::PRODUCT_TYPE_CODE){
	        $rewriteData = $read->fetchAll("select * from ".$tprefix."core_url_rewrite where id_path ='".$idPath."'");
	        if(count($rewriteData) > 0){
	        	foreach($rewriteData as $data){
		    		if($data['target_path'] != $targetPath){
		    			$write->query("update ".$tprefix."core_url_rewrite set target_path = '".$targetPath."' where url_rewrite_id='".$data['url_rewrite_id']."'");
                                        
		    		}
	        	}	    		
	    	}else{   		
	    		$write->query("insert into ".$tprefix."core_url_rewrite (store_id,product_id,id_path,request_path,target_path,is_system) values ($storeId,$productId,'$idPath','$requestPath','$targetPath',1)");
	        }
	    
        }else{
        	return;
        }	
    	
         /* In newsletter subscribers got mail when admin add new hotel - starts  */         
        if($saveObject->getIsDuplicate()!=1 && $saveObject->getCreatedAt() == $saveObject->getupdatedAt() && $saveObject->getTypeId()=='hotel'){ 
        
            $customers = Mage::getModel('customer/customer')
                                      ->getCollection()
                                      ->addAttributeToSelect('*');
            foreach($customers as $customer){

                $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($customer->getEmail());
                if($subscriber->getId())
                {
                                         
                     /* Sender Email */
                    $merchantMailId   = Mage::getStoreConfig('trans_email/ident_general/email');
                   /* Sender Name */
                    $merchantName     = Mage::getStoreConfig('trans_email/ident_general/name'); 
                    $templeId       =  (int)Mage::getStoreConfig('reservation/reservation_custom_email/cancelorder_custom_template');

                  //if it is user template then this process is continue
                   if ($templeId) {
                       $emailTemplate = Mage::getModel('core/email_template')->load($templeId);
                   } else {   //  we are calling default template
                       $emailTemplate = Mage::getModel('core/email_template')
                                       ->loadDefault('reservation_reservation_custom_email_newproduct_custom_template');
                   }
         
                   
                   $emailTemplate->setSenderName($merchantName);     //mail sender name
                   $emailTemplate->setSenderEmail( $merchantMailId);  //mail sender email id

                   $emailTemplateVariables = (array('ownername'=> $merchantName,'orderid' =>$orderid));
                   
                   $emailTemplateVariables['customerName'] = $customer->getName();
                   
                   $emailTemplateVariables['productName'] = $saveObject->getName();
                   
                   $emailTemplateVariables['productDesc'] = $saveObject->getDescription();
                   
                   $emailTemplateVariables['productUrl'] =  Mage::getBaseUrl().$requestPath;
                   
                   if($saveObject->getImageUrl()!=''){
                       $emailTemplateVariables['productImageUrl'] =  $saveObject->getImageUrl();
                   }else{
                       $emailTemplateVariables['productImageUrl'] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . DS . 'frontend' . DS . 'default' . DS . 'default' . DS . 'images' . DS . 'np_product_main.gif';
                   }
                                      
                   $emailTemplateVariables['storeName'] = Mage::getStoreConfig("general/store_information/name");
                   
                   $emailTemplateVariables['storeLink'] = Mage::getBaseUrl();

                   $emailTemplate->setDesignConfig(array('area' => 'frontend'));

                   $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables); //it return the temp body
                   
                 
                   $emailTemplate->send($customer->getEmail(), $merchantName, $emailTemplateVariables);  //send mail to customer email ids
                   
                 
                }

            }
           
       } 
     /* In newsletter subscribers got mail when admin add new hotel - ends  */         
    }
    
    public function catalogProductPrepareSave($observer){
    	$request = $observer->getEvent()->getRequest();
    	$product = $observer->getEvent()->getProduct();
    	$data = $request->getPost();
    	
    	$data['product']['stock_data']['use_config_manage_stock'] = 0;
    	$data['product']['stock_data']['manage_stock'] = 0;
    	$productId  = (int) $request->getParam('id');
    	if($product->getTypeId() == Apptha_Reservation_Helper_Data::PRODUCT_TYPE_CODE){ 
               
			$stockData = $product->getStockData();
			$stockData['use_config_manage_stock'] = 0;
			$stockData['manage_stock'] = 0;
    		$product->setStockData($stockData);
                       
    	}
        
    	return $product;
    	
    }
    
    public function saveInventory($quoteItem, $params){
    	$inventoryModel = Apptha_Reservation_Helper_Data::_getHotelInventory();
    	$hotelQuoteModel = Apptha_Reservation_Helper_Data::_getHotelOrders();
    	$roomTypeModel = Apptha_Reservation_Helper_Data::_getRoomType();
    	$days = Apptha_Reservation_Helper_Data::getDays($params['check-in'], $params['check-out']);
    	$fromTimeStamp = Apptha_Reservation_Helper_Data::toTimestamp($params['check-in']);
    	$oneDay = Apptha_Reservation_Helper_Data::ONE_DAY;
    	$roomTypeId = $params['roomtype'];
        $isRoom = 1;
        
        $timestamp = ''; 
    	for($i = 1; $i <= $days; $i++){
        	$inventoryCollection = $inventoryModel->getCollection();
    		if($i == 1):
        		$timestamp = $fromTimeStamp;
        		$date = Apptha_Reservation_Helper_Data::strToDate($timestamp);
        		$date = Apptha_Reservation_Helper_Data::dateFormat($date);
        	else:
        		$timestamp = $timestamp + $oneDay;
        		$date = Apptha_Reservation_Helper_Data::strToDate($timestamp);
        		$date = Apptha_Reservation_Helper_Data::dateFormat($date);
        	endif;
        	
        	$inventoryCollect = $inventoryCollection->getRoomPeriodIdFilter($roomTypeId, ($timestamp), $quoteItem['product_id'], $quoteItem['order_id'])->getItems();
    		if(count($inventoryCollect) > 0){
        		foreach($inventoryCollect as $invent){
	        		$inventoryModel->load($invent->getId())
	        					->setRooms($params['rooms'])
			    				->setGuests($params['guests'])
			    				->setBookedDate($date)
			    				->setBookedDateTimestamp($timestamp)   
	        					->setQuoteId($quoteItem['quote_id'])
		    					->setOrderId($quoteItem['order_id'])     					
	        					->save();
        		}        		
        	}else{
        		//$hotelQuoteModel = Mage::getResourceModel('reservation/orders')->deleteByQuoteId($quoteItem->getId(), $quoteItem->getQuote()->getId());
    	
    			$inventoryModel->setEntityId($quoteItem['product_id'])
    				->setStoreId($quoteItem['store_id'])
    				->setIsRooms(1)
    				->setRoomId($roomTypeId)
    				->setRooms($params['rooms'])
    				->setGuests($params['guests'])
    				->setQuoteId($quoteItem['quote_id'])
    				->setOrderId($quoteItem['order_id'])
    				->setBookedDate($date)
    				->setBookedDateTimestamp($timestamp)
    				->save();
        	}
    		
    	}
    }
    
    public function saveStocks($quoteItem, $params){
    	$this->saveInventory($quoteItem, $params);
    	$roomTypeModel = Apptha_Reservation_Helper_Data::_getRoomType();
    	$days = Apptha_Reservation_Helper_Data::getDays($params['check-in'], $params['check-out']);
    	$fromTimeStamp = Apptha_Reservation_Helper_Data::toTimestamp($params['check-in']);
    	$oneDay = Apptha_Reservation_Helper_Data::ONE_DAY;
    	
        $roomTypeId = $params['roomtype'];
        $isRoom = 1;
        $timestamp = 0;
        $roomTypeModel = $roomTypeModel->load($roomTypeId);
       	$totalAvailable = $roomTypeModel->getRoomQuantity();
        
        for($i = 1; $i <= $days+1; $i++){
        	$stock = Apptha_Reservation_Helper_Data::_getHotelStock();
        	$inventoryModel = Apptha_Reservation_Helper_Data::_getHotelInventory();
        	$stockCollection = $stock->getCollection();
        	$inventoryCollection = $inventoryModel->getCollection();
        	if($i == 1):
        		$timestamp = $fromTimeStamp;
        		$date = Apptha_Reservation_Helper_Data::strToDate($timestamp);
        		$date = Apptha_Reservation_Helper_Data::dateFormat($date);
        	else:
        		$timestamp = $timestamp + $oneDay;
        		$date = Apptha_Reservation_Helper_Data::strToDate($timestamp);
        		$date = Apptha_Reservation_Helper_Data::dateFormat($date);
        	endif;
        	
        	$stockCollect = $stockCollection->getPeriodIdFilter($roomTypeId, ($timestamp), $quoteItem['product_id'])->getItems();
        	$inventoryCollect = $inventoryCollection->getRoomPeriodIdFilter($roomTypeId, ($timestamp), $quoteItem['product_id'], $quoteItem['order_id'])->getItems();
    		if(count($stockCollect) > 0){
        		foreach($stockCollect as $stk){
        			$roomsBooked = 0;
        			if(count($inventoryCollect) > 0){
		        		foreach($inventoryCollect as $invent){
		        			$roomsBooked = $invent->getRooms();
		        		}
        			}
	        		if(($stk->getRoomsAvailable() < $params['rooms'])){
	        			
	        		}else{
	        			$available = ($params['quoteId'])?intval(($stk->getRoomsAvailable() + $roomsBooked) - $params['rooms']):intval($stk->getRoomsAvailable() - $params['rooms']);
	        			$stock->load($stk->getId())
	        					->setRoomsAvailable($available)	   
	        					->setQuoteId($quoteItem['quote_id'])
		    					->setOrderId($quoteItem['order_id'])     					
	        					->save();
	        		}
        		}        		
        	}else{


        		$available = intval($totalAvailable - $params['rooms']);
        		$stock->setEntityId($quoteItem['product_id'])
		    			->setStoreId($quoteItem['store_id'])
		    			->setIsRooms($isRoom)
		    			->setRoomId($roomTypeId)
		    			->setRoomsAvailable($available)
		    			->setPeriodDate("$date")
		    			->setPeriodDateTimestamp($timestamp)		    			
		    			->setQuoteId($quoteItem['quote_id'])
		    			->setOrderId($quoteItem['order_id'])
		    			->save();
        	}
    		
    	}
    	
    }
    
 	public function resetSession(){
    	$session = Apptha_Reservation_Helper_Data::_getSession();
    	     
        $session->setBuyPrice('');
        $session->setCheckIn('');
        $session->setCheckOut('');
        $session->setRooms('');
        $session->setGuests('');
        $session->setRoomType('');
    }
    
    public function saveSession($param){
    	$session = Apptha_Reservation_Helper_Data::_getSession();
        $session->setBuyPrice($param['buyprice']);
        $session->setCheckIn($param['check-in']);
        $session->setCheckOut($param['check-out']);
        $session->setRooms($param['rooms']);
        $session->setGuests($param['guests']);
        $session->setRoomType($param['roomtype']);
    }
    
    public function saveCartItems($observer){
       
        $checkIn = ""; 
        $buyprice = "";
    	$paramsData = Mage::app()->getRequest()->getParams();
       
        if(isset($paramsData)){
            $params = $paramsData;
        }
        
        if (Mage::registry("hotel_order_saved")) { // Already into order            
                return;
        }
	    //$params = Mage::app()->getRequest()->getParams();
	    $hotelOrdersModel = Apptha_Reservation_Helper_Data::_getHotelOrders();
	    
	    $quoteItem = $observer->getItem();
	    //$quoteItem = $observer->getEvent();
        
	    $sku = $quoteItem->getSku();
    	if ($quoteItem->getProductType() != Apptha_Reservation_Helper_Data::PRODUCT_TYPE_CODE) {
            return;
        }
        
    
     	/** If item deleted from cart, don't process later */
        if ($quoteItem->isDeleted()) {
            return;
        }
        
    	/** If quote item is already in order item */
        if (Mage::getModel('sales/order_item')->load($quoteItem->getId(), 'quote_item_id')->getId()) {
            return;
        }
         
        $Product = $quoteItem->getProduct();
        $roomTypeModel = Apptha_Reservation_Helper_Data::_getRoomType();
        
        if(isset($params['roomtype'])){
        	$roomTypeModel = $roomTypeModel->load($params['roomtype']);
        	$roomType = $roomTypeModel->getRoomType();
        	$roomTypeId = $params['roomtype'];
        }else{
        	$roomType = '';
        	$roomTypeId = '';
        }
        if (!isset($params['cart'])) {
            if (isset($params['check-in'])) {
                $checkIn = $params['check-in'];
            }
            if (isset($params['check-out'])) {
                $checkOut = $params['check-out'];
            }
            if (isset($params['buyprice'])) {
                $buyprice = $params['buyprice'];
            }
            if (isset($params['rooms'])) {
                $rooms = $params['rooms'];
            }
            if (isset($params['guests'])) {
                $guests = $params['guests'];
            }
            
           
            $session = Apptha_Reservation_Helper_Data::_getSession();
            $days = Apptha_Reservation_Helper_Data::getDays($session->getCheckIn(), $session->getCheckOut());
            $rowTotal = ($days * $buyprice);
            $storeId = Mage::app()->getStore()->getStoreId();
            
            if ($checkIn != '' && $checkOut != '') {
                
                Mage::getResourceModel('reservation/orders')->deleteByQuoteId($quoteItem->getId(),$quoteItem->getQuote()->getId(),$storeId);
           
                $hotelQuoteModel =  Mage::getModel('reservation/orders') 
                        ->setProductId($quoteItem->getProductId())
                        ->setEntityId($quoteItem->getProductId())
                        ->setHotelName($quoteItem->getName())
                        ->setStoreId($quoteItem->getStoreId())
                        ->setRoomType($quoteItem->getName())
                        ->setRoomTypeId($roomTypeId)
                        ->setRooms($rooms)
                        ->setGuests($guests)
                        ->setSku($quoteItem->getSku())
                        ->setPeriodFrom("$checkIn")
                        ->setPeriodTo("$checkOut")
                        ->setRoomType($roomType)
                        ->setOrderId($quoteItem->getId())
                        ->setQuoteId($quoteItem->getQuote()->getId())
                        ->setDateOrdered(now())
                        ->setRoomPrice($buyprice)
                        ->setDays($days)
                        ->save();
                //$this->saveStocks($quoteItem, $params);
                $this->saveSession($params);
            }
         //   $this->resetSession();
        }
        //Mage::register('hotel_quote_saved', 1);
        return true;
    	
    } 
    
    public function deleteCartItems($observer){
    	
    	$quoteItem = $observer->getQuoteItem();
        if (!$quoteItem) {
            $quoteItem = $observer->getItem();
        }

        if ($quoteItem->getProductType() != Apptha_Reservation_Helper_Data::PRODUCT_TYPE_CODE) {
            return;
        }
        Mage::getResourceModel('reservation/orders')->deleteHotelQuoteItem($quoteItem);
        //Mage::getResourceModel('reservation/inventory')->deleteHotelQuoteInventoryItem($quoteItem);
    }
    
    public function saveHotelOrderItems($observer){
    	$items = $observer->getEvent()->getOrder()->getItemsCollection();
    	$incrementId = $observer->getEvent()->getOrder()->getIncrementId();
    	$orderItemId = $observer->getEvent()->getOrder()->getId();
    	
        
    	foreach($items as $item){
    		$product = Mage::getModel('catalog/product')->load($item->getProductId());
            if ($product->getTypeId() != Apptha_Reservation_Helper_Data::PRODUCT_TYPE_CODE) {
                return;
            }
	        $updateData = array(
	            'increment' => $incrementId,
	        	'order_item_id' => $orderItemId
	        );
	        $quoteItemId = $item->getQuoteItemId();
	        $quoteItem['order_id'] = $item->getQuoteItemId();
	        
	        $quoteItem['product_id'] = $item->getProductId();
	        $quoteItem['store_id'] = $item->getStoreId();
	        
	        Mage::getResourceModel('reservation/orders')->updateHotelOrder($updateData, $quoteItemId);
	        $hotelQuoteModel = Mage::getModel('reservation/orders')->getCollection();
	        
			$hotelQuotes = $hotelQuoteModel->getQuoteOrderProductIdFilter($item->getQuoteItemId(), $item->getProductId())->load();
			foreach($hotelQuotes as $quotes){
				$params['check-in'] = $quotes->getPeriodFrom();
				$params['check-out'] =$quotes->getPeriodTo();
				$params['rooms'] = $quotes->getRooms();
				$params['guests'] = $quotes->getGuests();
				$params['roomtype'] = $quotes->getRoomTypeId();
				$quoteItem['quote_id'] = $quotes->getQuoteId();
			}
                        
	        $this->saveStocks($quoteItem, $params);
	        $this->sendOrderEmail($items, $incrementId, $item->getProductId());
	        	        
    	}
    	$this->resetSession();
    	
    	Mage::register('hotel_order_saved', 1);
    	
        return true;
        
        
    }
    
    public function checkStock($observer){
    	Mage::unregister('error');
    	$items = $observer->getEvent()->getOrder()->getItemsCollection();
    	foreach($items as $item){
    		
    		$hotelQuoteModel = Mage::getModel('reservation/orders')->getCollection();	        
			$hotelQuotes = $hotelQuoteModel->getQuoteOrderProductIdFilter($item->getQuoteItemId(), $item->getProductId())->load();
			foreach($hotelQuotes as $quotes){
				$stockCollection = Mage::getModel('reservation/stock')->getCollection();
				$params['check-in'] = $quotes->getPeriodFrom();
				$params['check-out'] =$quotes->getPeriodTo();
				$params['rooms'] = $quotes->getRooms();
				$params['guests'] = $quotes->getGuests();
				$params['roomtype'] = $quotes->getRoomTypeId();
				
				$fromTimeStamp = Apptha_Reservation_Helper_Data::toTimestamp($quotes->getPeriodFrom());
		        
		        $stockCollection->getPeriodIdFilter($quotes->getRoomTypeId(), $fromTimeStamp, $item->getProductId())->load();
		        
		        if(count($stockCollection) != 0){
		        	foreach($stockCollection as $stk){
		        		if(($stk->getRoomsAvailable() < $params['rooms'])){
		        			$available[] = 0;
		        		}else{
		        			$available[] = 1;
		        		}
		        	}        		
		        }else{
		        	$rooms = 'Available';
		        	$available[] = 1;
		        }
			}  	
    	}
    	if(in_array(0, $available)):
        	$redirectUrl = Mage::getBaseUrl().'reservation/checkout/cart/index/error/1';
        	Mage::register('error', 1);
        	Mage::getSingleton('core/session')->addError('Sorry, Rooms not available in the date you chosen. Kindly choose another date.');
        	Mage::app()->getResponse()->setHeader("Location", $redirectUrl)->sendHeaders();
        endif;     	     
        
    }
    
    public function sendOrderEmail($items, $incrementId, $productId){
    	
    	$product = Mage::getModel('catalog/product')->load($productId);
    	$hotelEmail = $product->getAppthaHotelEmail();
    	$orderKey = $product->getAppthaHotelOrderKey();
    	$postObject = new Varien_Object();
    	$postObject->setData(array('increment_id'=>$incrementId));
    	$mailTemplate = Mage::getModel('core/email_template');
        $mailTemplate->setSenderName(Mage::getStoreConfig('design/head/default_title'));
        $mailTemplate->setSenderEmail(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT));
        
        /*$mailTemplate->setDesignConfig(array('area' => 'frontend'))
                     ->sendTransactional(
                       Mage::getStoreConfig(self::XML_PATH_ORDER_TEMPLATE),
                       Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                       $hotelEmail,
                       '',
                       array('order' => $postObject)
                       );*/
         $mailTemplate->send($hotelEmail,'',Mage::getStoreConfig(self::XML_PATH_ORDER_TEMPLATE));    	
    }
    
    public function OrderCancel($observer) {
    	
    	$order = $observer->getEvent()->getOrder();
		$orderId = $order->getIncrementId();
		$hotelQuoteModel = Mage::getModel('reservation/orders')->getCollection();	        
		$hotelQuotes = $hotelQuoteModel->getSalesIncrementIdFilter($orderId)->load();
		
		foreach($hotelQuotes as $quotes){
		$rooms = $quotes->getRooms();
		$roomTypeId = $quotes->getRoomTypeId();
		$id = $quotes->getOrderId();
		$storeId = $quotes->getStoreId();
		$quoteId = $quotes->getQuoteId();
		}
		Mage::getResourceModel('reservation/orders')->deleteByQuoteId($id,$quoteId,$storeId);
		Mage::getResourceModel('reservation/stock')->updateCancelStock($id,$quoteId,$roomTypeId,$rooms,$storeId);
    }

 	

public function salesConvertQuoteItemToOrderItem(Varien_Event_Observer $observer)
{
    $quoteItem = $observer->getItem();
    if ($additionalOptions = $quoteItem->getOptionByCode('additional_options')) {
        $orderItem = $observer->getOrderItem();
        $options = $orderItem->getProductOptions();
        $options['additional_options'] = unserialize($additionalOptions->getValue());
        $orderItem->setProductOptions($options);
 }
}

  public function afterAddToCart(Varien_Event_Observer $observer) {
        $response = $observer->getResponse();
        if(Mage::getStoreConfig('onestepcheckout/general/Activate_apptha_onestepcheckout') == 1){
        	$response->setRedirect(Mage::getUrl('onestepcheckout/'));
        }else{
        $response->setRedirect(Mage::getUrl('checkout/onepage'));
        }
        Mage::getSingleton('checkout/session')->setNoCartRedirect(true);
    }


 }
?>
