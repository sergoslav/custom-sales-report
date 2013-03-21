<?php

class Cafepress_CPWms_Block_Adminhtml_Sales_Order_Renderer_CustomerGroup
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $id = $row->getData('increment_id');

        $order = Mage::getModel('sales/order')->loadByIncrementId($id);
        $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
        $groups = Mage::getModel('customer/group')->getCollection()->getData();
        if ($order->getCustomerId()) {
            $html = $groups[$customer->getGroupId()]['customer_group_code'];
        } else {
            $html = $groups[0]['customer_group_code'];
        }

        return $this->_getEscapedValue($html);
    }

    protected function _getEscapedValue($value)
    {
        return addcslashes(htmlspecialchars($value), '\\\'');
    }
}