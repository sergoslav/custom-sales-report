<?php

class SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_ShippingName
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        return $row->getData('shipping_firstname') . ' ' . $row->getData('shipping_lastname');


        $id = $row->getData('increment_id');
        $order = Mage::getModel('sales/order')->loadByIncrementId($id);
        $html = $order->getShippingAddress()->getFirstname() . ' ' . $order->getShippingAddress()->getLastname();

        return $this->_getEscapedValue($html);
    }

    protected function _getEscapedValue($value)
    {
        return addcslashes(htmlspecialchars($value), '\\\'');
    }
}