<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_HotelController extends Mage_Core_Controller_Front_Action
{
    public function _construct()
    {

    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');

        $this->getLayout()->getBlock('head')->setTitle($this->__('Hotels'));

        $this->renderLayout();
    }

    public function viewAction()
    {
        $categoryId = (int) $this->getRequest()->getParam('category', false);
        $productId  = (int) $this->getRequest()->getParam('id');
        $specifyOptions = $this->getRequest()->getParam('options');

        // Prepare helper and params
        $viewHelper = Mage::helper('reservation/hotel_view');

        $params = new Varien_Object();
        $params->setCategoryId($categoryId);
        $params->setSpecifyOptions($specifyOptions);

        // Render page
        try {
            $viewHelper->prepareAndRender($productId, $this, $params);

        } catch (Exception $e) {
            if ($e->getCode() == $viewHelper->ERR_NO_PRODUCT_LOADED) {
                if (isset($_GET['store'])  && !$this->getResponse()->isRedirect()) {
                    $this->_redirect('');
                } elseif (!$this->getResponse()->isRedirect()) {
                    $this->_forward('noRoute');
                }
            } else {
                Mage::logException($e);
                $this->_forward('noRoute');
            }
        }
    }

    public function availabilityAction(){
        /*$write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $procedureResult = $write->query("CALL GetOrders(10)");*/
        $from = $this->getRequest()->getParam('from');
        $date = $this->getRequest()->getParam('to');
        //$to = date('Y-j-n', strtotime("-1 day"));
        $to = date ('d-m-Y', strtotime('-1 day'.$date));
        //print_r($to);die;
        $id = $this->getRequest()->getParam('roomtype');
        $orderId = $this->getRequest()->getParam('quote_order');
        $requestedRooms = $this->getRequest()->getParam('rooms');
        $requestedGuests = $this->getRequest()->getParam('guests');
        $productId = $this->getRequest()->getParam('product');
        $days = Apptha_Reservation_Helper_Data::getDays($from, $to);
        $fromTimeStamp = Apptha_Reservation_Helper_Data::toTimestamp($from);
        $toTimeStamp = Apptha_Reservation_Helper_Data::toTimestamp($to);
        $oneDay = Apptha_Reservation_Helper_Data::ONE_DAY;

        $arrExcludeDatesSingle = Mage::getModel('reservation/excludedays')->getCollection()->getExcludeDates($productId,'single');
        $arrExcludeDatesPeriod = Mage::getModel('reservation/excludedays')->getCollection()->getExcludeDates($productId,'period');
        $arrExcludeDatesPeriodRes = Mage::getModel('reservation/excludedays')->getExcludeDatesPeriodRes($arrExcludeDatesPeriod);
        $arrAvailDates = Mage::getModel('reservation/excludedays')->getavailabilityDatesRes($arrExcludeDatesSingle,$arrExcludeDatesPeriodRes);
        $result = preg_replace('"', "", $arrAvailDates);
        $arrExcludeAvailDates = explode(",", $result);

        $roomCollection = Mage::getModel('reservation/roomtypes')->getCollection()
                            ->addEntityIdFIlter($productId);

        $notAvailable = '';
        $available = array();
        $availableDetailsHTML = '';
        $inventoryModel = Apptha_Reservation_Helper_Data::_getHotelInventory();


        $status = 1;
        foreach($roomCollection as $room):

            $inventoryCollection = $inventoryModel->getCollection();
            $inventoryCollect = $inventoryCollection->getRoomPeriodIdFilter($room->getId(), ($fromTimeStamp), $productId, $orderId)->getItems();


            if(count($inventoryCollect) > 0){
                foreach($inventoryCollect as $invent){
                    $roomsBooked = $invent->getRooms();
                }
            }
            $stock = '';
            $rooms = '';


            $dateAvail = Apptha_Reservation_Helper_Data::strToDate($fromTimeStamp);
            $strDateFrom = $dateAvail->get(Zend_Date::YEAR.'-'.Zend_Date::MONTH.'-'.Zend_Date::DAY);
                $dateAvail = Apptha_Reservation_Helper_Data::strToDate($toTimeStamp);
            $strDateTo = $dateAvail->get(Zend_Date::YEAR.'-'.Zend_Date::MONTH.'-'.Zend_Date::DAY);
            for($i = 1; $i <= $days+1; $i++){
                if($i == 1){
                    $timestamp = $fromTimeStamp;
                }
            else{
                $timestamp = $timestamp + $oneDay;
            }
            $HotelStock = Apptha_Reservation_Helper_Data::_getHotelStock();
            $stockCollection = $HotelStock->getCollection();
            $stock = $stockCollection->getPeriodIdFilter($room->getId(), $timestamp, $productId)->getItems();
            //echo count($stock);
            $aryRange=array();

                  $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
                  $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

                  if ($iDateTo>=$iDateFrom)
                  {
                    array_push($aryRange,date('Y-n-j',$iDateFrom)); // first entry
                    while ($iDateFrom<$iDateTo)
                    {
                        $iDateFrom+=86400; // add 24 hours
                        array_push($aryRange,date('Y-n-j',$iDateFrom));
                    }
                  }
                    //print_r($aryRange);
                  //if(in_array($arrExcludeAvailDates, $aryRange))
                  $block = array_intersect($aryRange, $arrExcludeAvailDates);
                  $blockDays = count($block);

                   if($blockDays > 0){
                    $rooms = 'Not Available';
                    $available[] = 0;
                }

            if(count($stock) != 0){
                foreach($stock as $stk){

                    if(($stk->getRoomsAvailable() < $requestedRooms)){
                        $rooms = 'Not Available';
                        $available[] = 0;
                    }else{
                        $rooms = 'Available';
                        $available[] = 1;
                    }
                }
            }else{
                $rooms = 'Available';
                $available[] = 1;
            }
        }
            //$availableDetailsHTML .= "<li>$dateAvail : $rooms</li>";
        endforeach;
        if(in_array(0, $available)):
            $errorMsg = $this->__("Sorry! Rooms not available. Try some other room or date");
            $notAvailable = '<span style="color:red;font-weight:bold;">'.$errorMsg.'</span>';
            $status = 0;
        endif;
        $availableDetails['msg'] = "$notAvailable<ul>$availableDetailsHTML</ul>";
        $availableDetails['status'] = $status;
        $availableDetails['available'] = $available;
        echo Zend_Json::encode($availableDetails);
        exit;
    }

    public function bookingavailabilityAction(){
        $arrAvailDates = '';
        $from = $this->getRequest()->getParam('from');
        $date = $this->getRequest()->getParam('to');
        //$to = date('Y-j-n', strtotime("-1 day"));
        $to = date ('d-m-Y', strtotime($date));

        $id = $this->getRequest()->getParam('roomtype');
        $orderId = $this->getRequest()->getParam('quote_order');
        $requestedRooms = $this->getRequest()->getParam('rooms');
        $requestedGuests = $this->getRequest()->getParam('guests');
        $roomTypeId = $this->getRequest()->getParam('roomtype');
        $productId = $this->getRequest()->getParam('product');
        $days = Apptha_Reservation_Helper_Data::getDays($from, $to);
        $fromTimeStamp = Apptha_Reservation_Helper_Data::toTimestamp($from);
        $toTimeStamp = Apptha_Reservation_Helper_Data::toTimestamp($to);

        $oneDay = Apptha_Reservation_Helper_Data::ONE_DAY;

        $arrExcludeDatesSingle = Mage::getModel('reservation/excludedays')->getCollection()->getExcludeDates($productId,'single');
        $arrExcludeDatesPeriod = Mage::getModel('reservation/excludedays')->getCollection()->getExcludeDates($productId,'period');
        $arrExcludeDatesPeriodRes = Mage::getModel('reservation/excludedays')->getExcludeDatesPeriodRes($arrExcludeDatesPeriod);
        $arrAvailDates = Mage::getModel('reservation/excludedays')->getavailabilityDatesRes($arrExcludeDatesSingle,$arrExcludeDatesPeriodRes);
        $result = preg_replace('/\"/', "", $arrAvailDates);
        $arrExcludeAvailDates = explode(",", $result);
        //print_r($arrExcludeAvailDates);
        $notAvailable = '';
        $available = array();
        $availableDetailsHTML = '';


            $status = 1;
            $stock = '';
            $rooms = '';
            $dateAvail = Apptha_Reservation_Helper_Data::strToDate($fromTimeStamp);
            $strDateFrom = $dateAvail->get(Zend_Date::YEAR.'-'.Zend_Date::MONTH.'-'.Zend_Date::DAY);
                $dateAvail = Apptha_Reservation_Helper_Data::strToDate($toTimeStamp);
            $strDateTo = $dateAvail->get(Zend_Date::YEAR.'-'.Zend_Date::MONTH.'-'.Zend_Date::DAY);

                 $aryRange=array();

                 $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));

                 $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

                  if ($iDateTo >= $iDateFrom)
                  {

                    array_push($aryRange,date('Y-n-j',$iDateFrom)); // first entry
                    while ($iDateFrom < $iDateTo)
                    {
                        $iDateFrom+=86400; // add 24 hours
                        array_push($aryRange,date('Y-n-j',$iDateFrom));
                    }
                  }

                  //if(in_array($arrExcludeAvailDates, $aryRange))
                  $block = array_intersect($aryRange, $arrExcludeAvailDates);

                  $blockDays = count($block);
            for($i = 1; $i <= $days+1; $i++){
                if($i == 1){
                    $timestamp = $fromTimeStamp;
                }
            else{
                $timestamp = $timestamp + $oneDay;
            }
            $HotelStock = Apptha_Reservation_Helper_Data::_getHotelStock();
            $stockCollection = $HotelStock->getCollection();
            $stock = $stockCollection->getPeriodIdFilter($id, ($timestamp), $productId)->load();
            //echo count($stock);
                if($blockDays > 0){
                    $rooms = 'Not Available';
                    $available[] = 0;
                }
            if(count($stock) != 0){
                foreach($stock as $stk){

                    if(($stk->getRoomsAvailable() < $requestedRooms)){
                        $rooms = 'Not Available';
                        $available[] = 0;
                    }
                    else{
                        $rooms = 'Available';
                        $available[] = 1;
                    }
                }
        }
            else{
            $rooms = 'Available';
            $available[] = 1;
            }
        }
            //$availableDetailsHTML .= "<li>$dateAvail : $rooms</li>";
        //endfor;
        if(in_array(0, $available)):
            $errorMsg = $this->__("Sorry! Rooms not available in few dates.Try some other room or date");
            $notAvailable = '<span style="color:red;font-weight:bold;">'.$errorMsg.'</span>';
            $status = 0;
        endif;
        $availableDetails['msg'] = "$notAvailable<ul>$availableDetailsHTML</ul>";
        $availableDetails['status'] = $status;

         $roomTypeModel = Apptha_Reservation_Helper_Data::_getRoomType()->load($roomTypeId);

         if($roomTypeModel->getRoomSpecialPrice()==0){
             $bp = $roomTypeModel->getRoomPricePerNight();
         }else{
              $bp = $roomTypeModel->getRoomSpecialPrice();
         }

        Mage::getSingleton('core/session')->setGuests($guests);
        Mage::getSingleton('core/session')->setFromdate($from);
        Mage::getSingleton('core/session')->setTodate($requestedGuests);


        echo Zend_Json::encode($availableDetails);
        exit;
    }

    ///cancelorder
  public function cancelorderAction() {

        $this->loadLayout();
        $this->renderLayout();

        $orderid = (int)$this->getRequest()->getParam('orderid');

        $orderincreid =  $this->getRequest()->getParam('orderincreid');

        Mage::getModel('reservation/cancel')->cancelOrder($orderid,$orderincreid);

        Mage::getSingleton('core/session')->addSuccess($this->__("Cancellation Request Submitted Successfully"));


        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');
        $write = $resource->getConnection('core_write');

        $tablename = Mage::getSingleton('core/resource')->getTableName('apptha_booking_hotel_orders');

        $write->query("update ".$tablename." set is_cancel = 1 where increment_id='".$orderincreid."'");

        if($this->getRequest()->getParam('cancel')==1){
            $this->_redirect('customer/account/');
        }else{
            $this->_redirect('sales/order/history');
        }

        return;

    }

    //pagination for product reviews
    public function paginationreviewAction(){

        $varhtml = '';

        $output = array();

        $productId = $this->getRequest()->getParam('productId');
        $pageNum = $this->getRequest()->getParam('pagenum');
        $limit = $this->getRequest()->getParam('limit');
        $pagelen = $this->getRequest()->getParam('pagelen');

        $_reviewsCount = Mage::getModel('review/review')->getCollection()
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
            ->addEntityFilter('product', $productId);

        $total = $pageNum * $limit;
        if($pagelen != $pageNum){
               $amountHtml = 'Items '. $pageNum .' to '. $total .' of '. $_reviewsCount->getSize().' total';
        }else{
             $amountHtml = '<strong>'.$_reviewsCount->getSize().' Item(s)</strong>';
        }

        $_items = Mage::getModel('review/review')->getCollection()
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
            ->addEntityFilter('product', $productId)
            ->setPageSize($limit)
            ->setCurPage($pageNum)
            ->setDateOrder()
            ->addRateVotes();

        foreach ($_items as $_review):
            $varhtml .= '<dt><a href="#customer-reviews">'.$_review->getTitle().'</a>&nbsp;'.$this->__('Review by <span>%s</span>', $_review->getNickname()).'&nbsp;</dt><dd>';
                $_votes = $_review->getRatingVotes();
                if (count($_votes)):
            $varhtml .='<table class="ratings-table"><col width="1" /><col /><tbody>';
                foreach ($_votes as $_vote):
            $varhtml .='<tr><th>'.$_vote->getRatingCode().'</th><td><div class="rating-box"><div class="rating" style="width:'.$_vote->getPercent().'%"></div></div> </td></tr>';
                endforeach;
            $varhtml .= '</tbody></table>';
                endif;
            $varhtml .= nl2br($_review->getDetail()).'<small class="date">'.$this->__('(Posted on %s)', Mage::helper('core')->formatDate($_review->getCreatedAt()), 'long').'</small></dd>';
        endforeach;

        $output['varhtml'] = $varhtml;
        $output['amounthtml'] = $amountHtml;
        echo Zend_Json::encode($output);
        exit;

    }

}
