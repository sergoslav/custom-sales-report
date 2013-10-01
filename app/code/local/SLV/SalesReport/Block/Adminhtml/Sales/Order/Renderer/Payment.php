<?php

class SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_Payment extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $customerId = $row->getData('customer_id');
        $customer = Mage::getModel('customer/customer')->load($customerId);

        if ($customer->getId()) {
            return $customer->getResource()->getAttribute('terms')->getFrontend()->getValue($customer);
            return $customer->getResource()->getAttributeText('terms');
        }

        return false;
    }

}