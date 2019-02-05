<?php
/**
 * @name         :  Apptha One Step Checkout
 * @version      :  1.7
 * @since        :  Magento 1.4
 * @author       :  Apptha - http://www.apptha.com
 * @copyright    :  Copyright (C) 2011 Powered by Apptha
 * @license      :  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Creation Date:  June 20 2011
 * @Modified By  :  Bala G
 * @Modified Date:  August 1 2013
 *
 * */

class Apptha_Onestepcheckout_Model_Activate extends Mage_Core_Model_Abstract
{
 	public function _construct()
    {
        parent::_construct();
        $this->_init('onestepcheckout/activate');
    }
    public function toOptionArray()
    {
        $activatePage = array('Cart Page', 'Seperate Page');
        $activateValue = array('1', '2');
        $arrayCombine = array_combine($activatePage,$activateValue);
        $temp = array();

        foreach($arrayCombine as $activatePageKey=>$activatePageValue)	{
            $temp[] = array('label' => $activatePageKey, 'value' => strtolower($activatePageValue));
        }
        return $temp;
    }
}