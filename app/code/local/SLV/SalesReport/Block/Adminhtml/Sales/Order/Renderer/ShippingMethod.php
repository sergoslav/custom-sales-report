<?php

class SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_ShippingMethod
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $shippingDescription = $row->getData('shipping_description');

        if (strpos($shippingDescription,'Select Shipping Method - ') !== false) {
            $shippingDescription = substr($shippingDescription,25);
        }

        return $shippingDescription;
    }
}