<?php

class SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_CustomerName
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $order)
    {
//        $id = $row->getData('increment_id');
//
//        $order = Mage::getModel('sales/order')->loadByIncrementId($id);
        $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
        if ($order->getCustomerId()) {
            $html = $customer->getFirstname() . ' ' . $customer->getLastname();
        } else {
            $html = '';
        }

        return $this->_getEscapedValue($html);
    }

    protected function _getEscapedValue($value)
    {
        return addcslashes(htmlspecialchars($value), '\\\'');
    }
}