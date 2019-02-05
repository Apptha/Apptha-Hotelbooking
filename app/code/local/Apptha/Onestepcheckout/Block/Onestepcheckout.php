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

class Apptha_Onestepcheckout_Block_Onestepcheckout extends Mage_Checkout_Block_Onepage_Abstract {
//get default country and set estimate rates
    public function _construct() {
        parent::_construct();

        $rates = $this->getEstimateRates();

        $defaut_country = Mage::getStoreConfig('onestepcheckout/general/default_country_id');
        if (!$defaut_country) {
            $defaut_country = 'US';
        }

        //$this->getQuote()->getShippingAddress()->setCountryId($defaut_country)->setCollectShippingRates(true)->save();
    	
	}
//get all shipping rates 
    public function getEstimateRates() {
        if (empty($this->_rates)) {
            $groups = $this->getQuote()->getShippingAddress()->getGroupedAllShippingRates();
            $this->_rates = $groups;
        }
        return $this->_rates;
    }

    public function _prepareLayout() {

        $title = Mage::getStoreConfig('onestepcheckout/general/checkout_title');
        if ($title) {
            $checkout_title = $title;
        } else {
            $checkout_title = "Onestep Checkout";
        }
        $this->getLayout()->getBlock('head')->setTitle($checkout_title);
        return parent::_prepareLayout();
    }
    //get shipping methods 
	public function shippingmethods($shipping,$methods)
	{
	if(($shipping)&&($methods))
	{
		return true;	
	}
	
	}
//getting steps based on the product
    public function getSteps() {
        $steps = array();

       //steps for virtual product
        if ($this->getOnepage()->getQuote()->isVirtual())
        {
            $stepCodes = array('billing', 'payment', 'review');
        }
        //steps for other product
        else
        {
            $stepCodes = array('billing', 'shipping', 'shipping_method', 'payment', 'review');
        }

        foreach ($stepCodes as $step)
        {

            $steps[$step] = $this->getCheckout()->getStepData($step);
        }
      
        return $steps;
    }

//check the active step
    public function getActiveStep()
    {
        return $this->isCustomerLoggedIn() ? 'billing' : 'login';
    }

    public function getOnepage()
    {
        return Mage::getSingleton('checkout/type_onepage');
    }

    //get product is virtual product  or not
    public function getVirtual()
    {
        if ($this->getOnepage()->getQuote()->isVirtual())
        {
            return true;
        } 
        else
        {
            return false;
        }
    }

}