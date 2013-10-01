<?php

class SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_TotalRefunded extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $refunded = 0;
        $collection = $row->getCreditMemosCollection();
        foreach($collection as $creditmemo){
            $refunded += $creditmemo->getGrandTotal();
        }

        return $refunded;
    }

    protected function _getEscapedValue($value)
    {
        return addcslashes(htmlspecialchars($value),'\\\'');
    }
}