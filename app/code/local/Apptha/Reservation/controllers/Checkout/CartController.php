<?php

/**
 * Contus Support Interactive.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file GCLONE-LICENSE.txt.
 * The CONTUS GCLONE License is available at this URL:
 * http://www.groupclone.net/GCLONE-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Contus Support does not guarantee correct work of this package
 * on any other Magento edition except Magento COMMUNITY edition.
 * =================================================================
 */
/**
 * Shopping cart controller
 */
require_once 'Mage/Checkout/controllers/CartController.php';

class Apptha_Reservation_Checkout_CartController extends Mage_Checkout_CartController {

    
  public function addAction()
    {
       
//Mage::getSingleton('checkout/session')->clear();
      
        $cart = $this->_getCart();
        $params = $this->getRequest()->getParams();
        
         $session = Mage::getSingleton('checkout/session');
         
        //Mage::getSingleton('checkout/cart')->removeItem( $item->getId() )->save();

        $productId = '';
        $fromdate = '';
        $todate = '';
        $serviceFee = '';
        $accomodate = '';
        $prod_id = '';
        $subtotal_amt = '';
        $guests = '';
        foreach ($session->getQuote()->getAllItems() as $item) {

            $productId = $item->getProductId();
           
            $productType = Mage::getModel('catalog/product')->load($productId);
       $existid = $productType->getTypeID();
            
        }

         $cartItems = Mage::helper('checkout/cart')->getCart()->getItemsCount();


       
          $productType = Mage::getModel('catalog/product')->load($params['product']);
       $productType->getTypeID();
        
        
        
            if ($cartItems >= 1) { if ($params['product'] != $productId) { if (($productType->getTypeID() == $existid)) {
                
            	
            	//$params['qty'] = NULL;
                $this->_getSession()->addError($this->__('Maximum one Hotel property can be added.'));
                $this->_goBack();
                return;
           }
        }
        }
        /* end */
      
        try {
        	/*Setting cart session values*/
             if(isset($params['fromdate'])){
              $fromdate = date("Y-m-d", strtotime(str_replace('@', '/', $params['fromdate'])));
       } 	
       if(isset($params['todate'])){
     	$todate = date("Y-m-d", strtotime(str_replace('@', '/', $params['todate'])));
       }if(isset($params['serviceFee'])){ 
        $serviceFee = $params['serviceFee'];
       }if(isset($params['accomodate'])){ 
     	$accomodate = str_replace('@', '/', $params['accomodate']); 
        $prod_id = $params['accomodate']; 
       }if(isset($params['subtotal_amt'])){ 
     	$subtotal_amt = $params['subtotal_amt'];
       } 
     	if(isset($params['guests'])){ 
        $guests = $params['guests'];
        }
      
        Mage::getSingleton('core/session')->setGuests($guests);
     	Mage::getSingleton('core/session')->setFromdate($fromdate);
     	Mage::getSingleton('core/session')->setTodate($todate);
     	Mage::getSingleton('core/session')->setAccomodate($accomodate);
        Mage::getSingleton('core/session')->setProdId($prod_id);
        Mage::getSingleton('core/session')->setSubtotal($subtotal_amt);
        Mage::getSingleton('core/session')->setServiceFee($serviceFee);
        	/*End*/
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }

            $product = $this->_initProduct();
            $related = $this->getRequest()->getParam('related_product');

            /**
             * Check product availability
             */
            if (!$product) {
                $this->_goBack();
                return;
            }

            $cart->addProduct($product, $params);
            if (!empty($related)) {
                $cart->addProductsByIds(explode(',', $related));
            }

            $cart->save();

            $this->_getSession()->setCartWasUpdated(true);

            
            Mage::dispatchEvent('checkout_cart_add_product_complete',
                array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
            );

            if (!$this->_getSession()->getNoCartRedirect(true)) {
                if (!$cart->getQuote()->getHasError()){
                    $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->htmlEscape($product->getName()));
                    $this->_getSession()->addSuccess($message);
                }
                $this->_goBack();
            }
        } catch (Mage_Core_Exception $e) {
            if ($this->_getSession()->getUseNotice(true)) {
                $this->_getSession()->addNotice($e->getMessage());
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->_getSession()->addError($message);
                }
            }

            $url = $this->_getSession()->getRedirectUrl(true);
            if ($url) {
                $this->getResponse()->setRedirect($url);
            } else {
                $this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());
            }
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('Cannot add the item to shopping cart.'));
            Mage::logException($e);
            $this->_goBack();
        }
    }
    
    
    
}
