<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Apptha_Reservation_Block_Adminhtml_Catalog_Product_Edit_Tab_Hotel extends Mage_Adminhtml_Block_Widget implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    /**
     * Reference to product objects that is being edited
     *
     * @var Mage_Catalog_Model_Product
     */
    protected $_product = null;

    protected $_config = null;

    /**
     * Class constructor
     *
     */
    public function _construct()
    {
        
        $this->setTemplate('reservation/product/edit/tab/hotel.phtml');
    }
    
    
    /**
     * Get tab label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('catalog')->__(' Information');
    }

    public function getTabTitle()
    {
        return Mage::helper('catalog')->__('Information');
    }
    
    protected function _toHtml()
    {
		
		
        $accordion = $this->getLayout()->createBlock('adminhtml/widget_accordion')
            ->setId('bookingInfo2');

       $accordion->addItem('samples2', array(
            'title'   => Mage::helper('adminhtml')->__('Booked Dates'),
            'content' =>'jjjj',
            'open'    => true,
        ));

        $this->setChild('accordion', $accordion);

        return parent::_toHtml();
    }
    
    /**
     * Detect if tab can be shown
     * @return bool
     */
    public function canShowTab(){
        return $this->getProduct()->getTypeId() == Apptha_Booking_Helper_Data::PRODUCT_TYPE_CODE;
    }

    /**
     * Check if tab is hidden
     * @return boolean
     */
    public function isHidden(){
        return !$this->canShowTab();
    }
    
     /**
     * Return current product
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct(){
	if(!$this->getData('product')){
	    $this->setData('product', Mage::registry('product'));
	}
	return $this->getData('product');
    }
    
}