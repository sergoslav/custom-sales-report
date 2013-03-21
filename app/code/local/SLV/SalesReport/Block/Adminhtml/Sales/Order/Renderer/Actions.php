<?php

class Cafepress_CPWms_Block_Adminhtml_Sales_Order_Renderer_Actions
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $id = $row->getData('entity_id');
        $order = Mage::getModel('sales/order')->load($id);
        $html = '';

        $html .= '<select class="action-select" onchange="varienGridAction.execute(this);">';
        $html .= '<option value=""></option>';
        $html .= '<option value="{&quot;href&quot;:&quot;' . str_replace(
            '/', '\/', Mage::getUrl('', array('_secure' => true)) . 'admin/sales_order/view/order_id/' . $id . '/'
        ) . '&quot;}">' . Mage::helper('sales')->__('View') . '</option>';
        $html .= '<option value="{&quot;href&quot;:&quot;' . str_replace(
            '/', '\/', Mage::getUrl('', array('_secure' => true)) . 'cpwms/adminhtml_wms/regenerate/id/' . $id . '/'
        ) . '&quot;}">' . Mage::helper('catalog')->__('WMS Regenerate') . '</option>';
        if ($order->getCustomNumber()) {
            $html .= '<option value="{&quot;confirm&quot;:&quot;' . Mage::helper('cpwms')->__(
                'Please confirm that you want to delete this order?'
            ) . '&quot;,&quot;href&quot;:&quot;' . str_replace(
                '/', '\/',
                Mage::getUrl('', array('_secure' => true)) . 'cpwms/adminhtml_cafepress/orderCancel/id/' . $id . '/'
            ) . '&quot;}">' . Mage::helper('cpwms')->__('CP Order Cancel') . '</option>';
        }
        $html .= '</select>';

        return $html;
    }

    protected function _getEscapedValue($value)
    {
        return addcslashes(htmlspecialchars($value), '\\\'');
    }
}