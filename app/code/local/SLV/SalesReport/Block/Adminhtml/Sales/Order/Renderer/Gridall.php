<?php

class Cafepress_CPWms_Block_Adminhtml_Sales_Order_Gridall extends Mage_Adminhtml_Block_Sales_Order_Grid
{

    public function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass())
            ->joinAttribute('shipping_firstname', 'order_address/firstname', 'shipping_address_id', null, 'left')
            ->joinAttribute('shipping_lastname', 'order_address/lastname', 'shipping_address_id', null, 'left')

            ->joinAttribute('street1', 'order_address/street1', 'shipping_address_id', null, 'left')
            ->joinAttribute('street2', 'order_address/stree2', 'shipping_address_id', null, 'left')

            ->joinAttribute('town', 'order_address/town', 'shipping_address_id', null, 'left')
            ->joinAttribute('zipcode', 'order_address/zipcode', 'shipping_address_id', null, 'left');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        $this->addColumn(
            'shipping_1', array(
                               'header'   => Mage::helper('sales')->__('Shipping Address'),
                               'index'    => 'shipping_address',
                               'renderer' => new Cafepress_CPWms_Block_Adminhtml_Sales_Order_Renderer_Shipping()
                          )
        );

        $this->addColumn(
            'street1', array(
                            'header' => Mage::helper('sales')->__('street1'),
                            'index'  => 'street1',
                       )
        );


        $this->addExportType('*/*/exportMoreDataCsv', Mage::helper('sales')->__('Full Data to CSV'));


        return $this;
    }
}
