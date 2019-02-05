<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class Apptha_Reservation_Block_Hotel_View_Media extends Mage_Catalog_Block_Product_View_Abstract
{
    protected $_isGalleryDisabled;

    public function getGalleryImages()
    {
        if ($this->_isGalleryDisabled) {
            return array();
        }
        $collection = $this->getProduct()->getMediaGalleryImages();
        return $collection;
    }

    public function getGalleryUrl($image=null)
    {
        $params = array('id'=>$this->getProduct()->getId());
        if ($image) {
            $params['image'] = $image->getValueId();
            return $this->getUrl('*/*/gallery', $params);
        }
        return $this->getUrl('*/*/gallery', $params);
    }

    public function disableGallery()
    {
        $this->_isGalleryDisabled = true;
    }
}
