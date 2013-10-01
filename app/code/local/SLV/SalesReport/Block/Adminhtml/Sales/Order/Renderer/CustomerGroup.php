<?php

class SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_CustomerGroup
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $customerId = $row->getData('customer_id');

        $groups = Mage::getModel('customer/group')->getCollection()->getData();
        if ($customerId) {
            $customer = Mage::getModel('customer/customer')->load($customerId);
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