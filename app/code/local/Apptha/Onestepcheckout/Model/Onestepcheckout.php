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


class Apptha_Onestepcheckout_Model_Onestepcheckout extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('onestepcheckout/onestepcheckout');
    }
    public function toOptionArray()
    {
        $colors = array('Country', 'Zip Code / Postal Code', 'State/region', 'City');
        $temp = array();

        foreach($colors as $color)	{
            $temp[] = array('label' => $color, 'value' => strtolower($color));
        }

        return $temp;
    }
}