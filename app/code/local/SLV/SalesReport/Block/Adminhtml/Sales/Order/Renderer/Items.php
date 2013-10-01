<?php
/**
 * Created by JetBrains PhpStorm.
 * User: svatoslavzilicev
 * Date: 10.11.12
 * Time: 10:35
 * To change this template use File | Settings | File Templates.
 */
class SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_Items extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $order)
    {
//        $id = $row->getData('increment_id');
//
//        $order = Mage::getModel('sales/order')->loadByIncrementId($id);
        $items = $order->getAllItems();
        $cost = 0;
        foreach($items as $item) {
            if ($item->getChildrenItems())
                continue;
//            var_dump((int)$item->getQtyOrdered()); exit;
           if (substr_count($item->getSku(),'GW') == 0){
               $cost += (int)$item->getQtyOrdered();
           }
//            $cost+=Mage::getModel('catalog/product')->load($item->getProductId())->getCost() * $item->getQtyOrdered();
        }



        return $cost;
    }

    protected function _getEscapedValue($value)
    {
        return addcslashes(htmlspecialchars($value),'\\\'');
    }
}