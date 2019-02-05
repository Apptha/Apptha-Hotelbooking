<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Block_Adminhtml_Orders_Hotel extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Set template
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_orders';
	    $this->_blockGroup = 'reservation';
	    $this->_headerText = Mage::helper('reservation')->__('Hotel Orders');
	    parent::__construct();
	    $this->removeButton('add');
	    
    }

    
    
    
}
