<?php

class Apptha_Reservation_Block_Wishlist_Customer_Wishlist_Item_Column_Cart extends Mage_Wishlist_Block_Customer_Wishlist_Item_Column_Cart
{
   
    public function getAddToCartQty(Mage_Wishlist_Model_Item $item)
    {
        $qty = $item->getQty();
        return $qty ? $qty : 1;
    }

    public function getJs()
    {
        $js = "
            function addWItemToCart(itemId) {
                var url = '" . $this->getItemAddToCartUrl('%item%') . "';
                url = url.gsub('%item%', itemId);
                var form = $('wishlist-view-form');
                if (form) {
                    var input = form['qty[' + itemId + ']'];
                    if (input) {
                        var separator = (url.indexOf('?') >= 0) ? '&' : '?';
                        url += separator + input.name + '=' + encodeURIComponent(input.value);
                    }
                }
                setLocation(url);
            }
        ";

        $js .= parent::getJs();
        return $js;
    }
}
