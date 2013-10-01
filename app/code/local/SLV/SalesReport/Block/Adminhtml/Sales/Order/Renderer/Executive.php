<?php

class SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_Executive extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
//    const STORE_ID = 5;

    public function render(Varien_Object $row)
    {
        $customerId = $row->getData('customer_id');
        $customer = Mage::getModel('customer/customer')->load($customerId);

        if ($customer->getId()) {
            $attribute = $customer->getResource()->getAttribute('account_exec_number');
//            $attribute->getSource()->getAttribute()->setStoreId(self::STORE_ID);

            return $attribute->getFrontend()->getValue($customer);
        }

        return false;
    	
    }

}