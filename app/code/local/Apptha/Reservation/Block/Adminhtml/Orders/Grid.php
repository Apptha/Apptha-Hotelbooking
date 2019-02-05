<?php
/**
 * @ Author     : Apptha team
 * @copyright   : Copyright (c) 2011 (www.apptha.com)
 * @license     : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Apptha_Reservation_Block_Adminhtml_Orders_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('bookingGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('desc');
        

    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection()
    {
        $store = $this->_getStore();
        //$collection = Mage::getModel('reservation/orders')->getCollection()
        			 // ->addOrderIdFilter();
                                
    $collection = Mage::getModel('reservation/orders')->getCollection();
        $collection->getSelect()
            ->join(
                    array('sales'=> Mage::getSingleton("core/resource")->getTableName('sales_flat_order')),
                    'sales.entity_id = main_table.order_item_id'
                    
                  );
        $this->setCollection($collection);
		return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id',
            array(
                'header'=> Mage::helper('reservation')->__('ID'),
                'width' => '50px',
                'type'  => 'number',
            	'filter'   => false,
            	'visible'=> false,
                'index' => 'id',
                'sortable'  => true,
        ));
        
        $this->addColumn('action',
            array(
                'header'=> Mage::helper('reservation')->__('Order Id'),
                'width'     => '250px',
                'type'      => 'number',
                'getter'     => 'getOrderItemId',
            	'renderer'  => 'Apptha_Reservation_Block_Adminhtml_Orders_Renderer_Order',
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'increment_id',
        ));
        
        /* $this->addColumn('increment_id',
            array(
                'header'=> Mage::helper('reservation')->__('Order Id'),
                'width' => '150px',
                'index' => 'increment_id',
        )); */
        
        $this->addColumn('period_from',
            array(
                'header'=> Mage::helper('reservation')->__('Check In'),
                'width' => '150px',
                'index' => 'period_from',
            	'type' => 'date',
        ));
        
        $this->addColumn('period_to',
            array(
                'header'=> Mage::helper('reservation')->__('Check Out'),
                'width' => '150px',
                'index' => 'period_to',
            	'type' => 'date',
        ));
        
        $this->addColumn('hotel',
            array(
                'header'=> Mage::helper('reservation')->__('Hotel Name'),
                'width'     => '200px',
                'type'      => 'action',
                'getter'     => 'getHotelName',
                'renderer'  => 'Apptha_Reservation_Block_Adminhtml_Orders_Renderer_Name',
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'hotel_name',
        ));
        
        $this->addColumn('sku',
            array(
                'header'=> Mage::helper('catalog')->__('SKU'),
                'width' => '250px',
                'index' => 'sku',
        ));
        
        
        
        $this->addColumn('room_type',
            array(
                'header'=> Mage::helper('reservation')->__('Room Type'),
                'width' => '250px',
                'index' => 'room_type',
        ));
        
        $this->addColumn('rooms',
            array(
                'header'=> Mage::helper('reservation')->__('Rooms' ),
                'width' => '100px',
                'index' => 'rooms',
        ));
        
        $this->addColumn('guests',
            array(
                'header'=> Mage::helper('reservation')->__('Guests' ),
                'width' => '100px',
                'index' => 'guests',
        )); 
        
        $this->addColumn('status', array(
            'header' => Mage::helper('reservation')->__('Status'),
            'index' => 'status',
            'type'  => 'options',
            'width' => '70px',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
            'renderer' => 'Apptha_Reservation_Block_Adminhtml_Orders_Renderer_Status'
        ));
        
        
        

        return parent::_prepareColumns();
    }
    
public function getRowUrl($row)
  {
      return $this->getUrl('adminhtml/sales_order/view', array('order_id' => $row->getOrderId()));
  }

       
}
