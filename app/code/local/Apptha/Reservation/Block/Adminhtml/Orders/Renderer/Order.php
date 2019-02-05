<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Block_Adminhtml_Orders_Renderer_Order extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
	{
		public function render(Varien_Object $row)
		{
                    //echo '<pre>';
                   // print_r($row);
		$value   =  $row->getData($this->getColumn()->getIndex());
		$link 	 =  $this->getUrl('adminhtml/sales_order/view', array('order_id' => $row->getOrderItemId()));
		return '<a href="'.$link.'" target=_blank>'.$value.'</a>';
		 
		}
	 
	}      
