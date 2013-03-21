<?php

class SLV_SalesReport_Block_Observer extends Mage_Core_Block_Abstract
{

    /**
     * @param $block Mage_Adminhtml_Block_Sales_Order_Grid
     */
    public function Mage_Adminhtml_Block_Sales_Order_Grid($block)
    {
        if (!Mage::registry('SLV_SalesReport_Mage_Adminhtml_Block_Sales_Order_Grid')) {
            $block->addExportType('slv_salesreport/adminhtml_sales_order/exportOrdersReport', Mage::helper('slv_salesreport')->__('Custom Orders Report (CSV)'));
            Mage::register('SLV_SalesReport_Mage_Adminhtml_Block_Sales_Order_Grid',true,true);
        }
    }

}