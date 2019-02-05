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
?>
<?php

class Apptha_Onestepcheckout_Block_Onestep_Review extends Mage_Checkout_Block_Onepage_Abstract {

    protected function _construct() {
        $this->getCheckout()->setStepData('review', array(
            'label' => Mage::helper('checkout')->__('Order Review'),
            'is_show' => $this->isShow()
        ));
        parent::_construct();

        $this->getQuote()->collectTotals()->save();
    }

    public function getItems() {
        return $this->getQuote()->getAllVisibleItems();
    }

    public function getTotals() {
        return $this->getQuote()->getTotals();
    }

}
