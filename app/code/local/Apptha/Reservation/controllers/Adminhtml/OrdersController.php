<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Adminhtml_OrdersController extends Mage_Adminhtml_Controller_Action
{
	public function _construct()
	{
		
	}
	/**
     * Index action
     */
    public function indexAction()
    {
        
        $this->_title($this->__('Catalog'))
             ->_title($this->__('Hotel Orders'));

        $this->loadLayout();
        $this->renderLayout();
    }
    
}
?>
