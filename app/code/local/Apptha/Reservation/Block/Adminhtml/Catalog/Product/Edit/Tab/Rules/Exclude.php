<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Apptha_Reservation_Block_Adminhtml_Catalog_Product_Edit_Tab_Rules_Exclude
    extends Mage_Adminhtml_Block_Widget
    implements Varien_Data_Form_Element_Renderer_Interface
{
    public function __construct(){
        $this->setTemplate('reservation/product/edit/tab/rules/exclude.phtml');
    }
    public function getProduct(){
        return Mage::registry('product');
    }
	public function render(Varien_Data_Form_Element_Abstract $element){
		$this->setElement($element);
		return $this->toHtml();
	}
    
    
    protected function _prepareLayout()
    {
        $this->setChild('add_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('catalog')->__('Add Day(s)'),
                    'onclick'   => 'excludedDaysControl.addItem()',
                    'class' => 'add'
                )));
                
        

                
        return parent::_prepareLayout();
    }
    

	/**
	 * Creates element HTML for dates input
	 * @param string $name
	 * @param string $type
	 * @return string
	 */
    public function createDateBlock($name, $type){
		
		$type = $type != 'to' ? 'from' : $type;
		
		$element = $this->getLayout()->createBlock('core/html_date')
                ->setImage(Mage::getDesign()->getSkinUrl('images/grid-cal.gif'))
                ->setFormat(Mage::app()->getLocale()->getDateStrFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT))
                ;


        
        if($type == 'from'){
			$days_select = $this->getLayout()->createBlock('core/html_select')
					->setName($name.'[__index__][recurrent_day]')
					->setId('excluded_days_row___index___recurrent_day')
					->setOptions(
						array(
							1 => $this->__('Monday'),
							2 => $this->__('Tuesday'),
							3 => $this->__('Wednesday'),
							4 => $this->__('Thursday'),
							5 => $this->__('Friday'),
							6 => $this->__('Saturday'),
							7 => $this->__('Sunday')
						)
					);
			
			$element
				->setName($name.'[__index__][period_from]')
				->setId('excluded_days_row___index___period_from')
				->setClass('product-custom-option datetime-picker input-text require booking-date');
				
			return $days_select->toHtml().$element->toHtml();	
		
		}else{
			$element
				->setName($name.'[__index__][period_to]')
				->setId('excluded_days_row___index___period_to') 
				->setClass('product-custom-option datetime-picker input-text require booking-date');
		}        
                
        return $element->toHtml();
	}

    
    public function setElement(Varien_Data_Form_Element_Abstract $element){
        $this->_element = $element;
        return $this;
    }

    public function getElement(){
        return $this->_element;
    }    
    
    
    public function getWebsites()
    {
		
        if (!is_null($this->_websites)) {
            return $this->_websites;
        }
        $websites = array();
        $websites[0] = array(
            'name'      => $this->__('All Websites'),
            'currency'  => Mage::app()->getBaseCurrencyCode()
        );
        if (Mage::app()->isSingleStoreMode() || $this->getElement()->getEntityAttribute()->isScopeGlobal()) {
            return $websites;
        }
        elseif ($storeId = $this->getProduct()->getStoreId()) {
            $website = Mage::app()->getStore($storeId)->getWebsite();
            $websites[$website->getId()] = array(
                'name'      => $website->getName(),
                'currency'  => $website->getConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            );
        }
        else {
            $websites[0] = array(
                'name'      => $this->__('All Websites'),
                'currency'  => Mage::app()->getBaseCurrencyCode()
            );
            foreach (Mage::app()->getWebsites() as $website) {
                if (!in_array($website->getId(), $this->getProduct()->getWebsiteIds())) {
                    continue;
                }
                $websites[$website->getId()] = array(
                    'name'      => $website->getName(),
                    'currency'  => $website->getConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
                );
            }
        }
        $this->_websites = $websites;
        return $this->_websites;
    }
    
    public function getValues(){
		return Mage::getModel('reservation/excludedays')->getCollection()
					->addEntityIdFilter($this->getProduct()->getId())
					->addStoreIdFilter($this->getProduct()->getStoreId())
					->getItems();
	}
}
