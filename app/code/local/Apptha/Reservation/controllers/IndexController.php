<?php

/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_IndexController extends Mage_Core_Controller_Front_Action {

    public function _construct() {
        
    }

    /**
     * Index action
     */
    public function indexAction() {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');

        $this->getLayout()->getBlock('head')->setTitle($this->__('Hotels'));

        $this->renderLayout();
    }

    public function searchAction() { 
        $data = $this->getRequest()->getParams();
        $roomres = Mage::getResourceModel('reservation/roomtypes')->getAvailablerooms($data);
        
        foreach ($roomres as $rooms) {
            echo $rooms->getId();
        }
    }

    public function roomtypeAction() {
        $p_Id = $this->getRequest()->getParam('p_Id');
        $r_type = $this->getRequest()->getParam('r_type');
        $noRooms = '';
        $noGuest = '';
        $periodFrom = '';
        $roomPeriodTo = '';
        $roomres = Mage::getResourceModel('reservation/roomtypes')->getHotelrooms($p_Id, $r_type);

        $roomQty = $roomres['room_quantity'];
        $guestQty = $roomres['room_capacity'];

        if (isset($roomres['room_period_from'])) {
            $periodFrom = $roomres['room_period_from'];
        }

        $currenttimestamp = strtotime(date("Y-m-d", Mage::getModel('core/date')->timestamp(time())));
        $fromtimestamp = strtotime($periodFrom);
        if ($currenttimestamp > $fromtimestamp) {
            $roomPeriodFrom = date("Y-m-d", Mage::getModel('core/date')->timestamp(time()));
        } else {

            $roomPeriodFrom = $periodFrom;
        }
        if (isset($roomres['room_period_to'])) {
            $roomPeriodTo = $roomres['room_period_to'];
        }

        for ($i = 1; $i <= $roomQty; $i++):
            $noRooms .='<option value ="' . $i . '" >' . $i . '</option>';
        endfor;
        for ($i = 1; $i <= $guestQty; $i++):
            $noGuest .='<option value ="' . $i . '" >' . $i . '</option>';
        endfor;
        echo json_encode(array("a" => $noRooms, "b" => $noGuest, "c" => $roomPeriodFrom, "d" => $roomPeriodTo));
    }
    
    

}
