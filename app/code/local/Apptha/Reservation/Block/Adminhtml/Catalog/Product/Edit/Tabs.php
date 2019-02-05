<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Block_Adminhtml_Catalog_Product_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    protected $_attributeTabBlock = 'adminhtml/catalog_product_edit_tab_attributes';

    public function __construct()
    {
        parent::__construct();
        $this->setId('product_info_tabs');
        $this->setDestElementId('product_edit_form');
        $this->setTitle(Mage::helper('catalog')->__('Hotel Information'));
    }

    protected function _prepareLayout()
    {
        $product = $this->getProduct();

        if (!($setId = $product->getAttributeSetId())) {
            $setId = $this->getRequest()->getParam('set', null);
        }

        if ($setId) {
            $groupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')->setOrder('attribute_group_id', 'ASC')
                ->setAttributeSetFilter($setId)
                ->load();
        
            foreach ($groupCollection as $group) {
                $attributes = $product->getAttributes($group->getId(), true);
                // do not add groups without attributes
              
                foreach ($attributes as $key => $attribute) {
                    if( !$attribute->getIsVisible() ) {
                        unset($attributes[$key]);
                    }
                }

                if (count($attributes)==0) {
                    continue;
                }
                //echo $this->getAttributeTabBlock();die;
                
                if($group->getAttributeGroupName() == 'Rules'){
                	$tabName = 'Room types & Prices';
                }else if($group->getAttributeGroupName() == 'Deal'){
                	$tabName = 'Hotel Deal';
                }else if($group->getAttributeGroupName() == 'Hotel Information'){
                	$tabName = 'More About Hotel';
                }else{
                	$tabName = $group->getAttributeGroupName();
                }                
				if($group->getId() != 16 && $group->getId() != 12 && $group->getId() != 17 ){
					$this->addTab('group_'.$group->getId(), array(
	                    'label'     => Mage::helper('catalog')->__($tabName),
	                    'content'   => $this->_translateHtml($this->getLayout()->createBlock($this->getAttributeTabBlock())
	                            ->setGroup($group)
	                            ->setGroupAttributes($attributes)
	                            ->toHtml()),
                	));
				}                
            }

//            $this->addTab('inventory', array(
//                'label'     => Mage::helper('catalog')->__('Inventory'),
//                'content'   => $this->_translateHtml($this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_inventory')->toHtml()),
//            ));


            /**
             * Don't display website tab for single mode
             */
            if (!Mage::app()->isSingleStoreMode()) {
                $this->addTab('websites', array(
                    'label'     => Mage::helper('catalog')->__('Websites'),
                    'content'   => $this->_translateHtml($this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_websites')->toHtml()),
                ));
            }
            
	    $this->addTab('categories', array(
                'label'     => Mage::helper('catalog')->__('Categories'),
                'url'       => $this->getUrl('*/*/categories', array('_current' => true)),
                'class'     => 'ajax',
            ));
            
            $this->addTab('related', array(
                'label'     => Mage::helper('catalog')->__('Related Hotels'),
                'url'       => $this->getUrl('*/*/related', array('_current' => true)),
                'class'     => 'ajax',
            ));

            
            $storeId = 0;
            if ($this->getRequest()->getParam('store')) {
                $storeId = Mage::app()->getStore($this->getRequest()->getParam('store'))->getId();
            }

            $alertPriceAllow = Mage::getStoreConfig('catalog/productalert/allow_price');
            $alertStockAllow = Mage::getStoreConfig('catalog/productalert/allow_stock');

            if ($alertPriceAllow || $alertStockAllow) {
                $this->addTab('productalert', array(
                    'label'     => Mage::helper('catalog')->__('Hotel Alerts'),
                    'content'   => $this->_translateHtml($this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_alerts', 'admin.alerts.products')->toHtml())
                ));
            }
            
            if( $this->getRequest()->getParam('id', false) ) {
                if (Mage::getSingleton('admin/session')->isAllowed('admin/catalog/reviews_ratings')){
                    $this->addTab('reviews', array(
                        'label' => Mage::helper('catalog')->__('Hotel Reviews'),
                        'url'   => $this->getUrl('*/*/reviews', array('_current' => true)),
                        'class' => 'ajax',
                    ));
                }
                if (Mage::getSingleton('admin/session')->isAllowed('admin/catalog/tag')){
                    $this->addTab('tags', array(
                     'label'    => Mage::helper('catalog')->__('Hotel Tags'),
                     'url'      => $this->getUrl('*/*/tagGrid', array('_current' => true)),
                     'class'    => 'ajax',
                    ));

//                    $this->addTab('customers_tags', array(
//                        'label' => Mage::helper('catalog')->__('Customers Tagged Hotel'),
//                        'url'   => $this->getUrl('*/*/tagCustomerGrid', array('_current' => true)),
//                        'class' => 'ajax',
//                    ));
                }

            }
//            if (!$product->isGrouped()) {
//                $this->addTab('customer_options', array(
//                    'label' => Mage::helper('catalog')->__('Custom Options'),
//                    'url'   => $this->getUrl('*/*/options', array('_current' => true)),
//                    'class' => 'ajax',
//                ));
//            }

        }
        else {
            $this->addTab('set', array(
                'label'     => Mage::helper('catalog')->__('Settings'),
                'content'   => $this->_translateHtml($this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_settings')->toHtml()),
                'active'    => true
            ));
        }
        return parent::_prepareLayout();
    }

    /**
     * Retrive product object from object if not from registry
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        if (!($this->getData('product') instanceof Mage_Catalog_Model_Product)) {
            $this->setData('product', Mage::registry('product'));
        }
        return $this->getData('product');
    }

    /**
     * Getting attribute block name for tabs
     *
     * @return string
     */
    public function getAttributeTabBlock()
    {
        if (is_null(Mage::helper('adminhtml/catalog')->getAttributeTabBlock())) {
            return $this->_attributeTabBlock;
        }
        return Mage::helper('adminhtml/catalog')->getAttributeTabBlock();
    }

    public function setAttributeTabBlock($attributeTabBlock)
    {
        $this->_attributeTabBlock = $attributeTabBlock;
        return $this;
    }

    /**
     * Translate html content
     * 
     * @param string $html
     * @return string
     */
    protected function _translateHtml($html)
    {
        Mage::getSingleton('core/translate_inline')->processResponseBody($html);
        return $html;
    }
}
