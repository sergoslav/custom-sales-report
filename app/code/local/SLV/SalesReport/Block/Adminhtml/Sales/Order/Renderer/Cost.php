<?php

class Cafepress_CPWms_Block_Adminhtml_Sales_Order_Renderer_Cost
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $id = $row->getData('increment_id');

        $order = Mage::getModel('sales/order')->loadByIncrementId($id);
        $items = $order->getAllItems();
        $cost = 0;
        foreach ($items as $item) {
            if ($item->getChildrenItems()) {
                continue;
            }
            $cost += Mage::getModel('catalog/product')->load($item->getProductId())->getCost() * $item->getQtyOrdered();
        }


        return $this->_getEscapedValue($cost);
    }

    protected function _getEscapedValue($value)
    {
        return addcslashes(htmlspecialchars($value), '\\\'');
    }
}