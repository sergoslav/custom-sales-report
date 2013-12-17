<?php
class SLV_SalesReport_Block_Adminhtml_Sales_Order_Gridall2 extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('sales_order_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected $_exportPageSize = false;

    /**
     * Retrieve collection class
     *
     * @return string
     */
    protected function _getCollectionClass()
    {
        return 'sales/order_collection';
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());
//            ->join(
//            'sales/order_item',
//            '`sales/order_item`.order_id=main_table.entity_id',
//            array(
//                 'skus' => new Zend_Db_Expr('group_concat(sales/order_item.sku SEPARATOR ",")'),
//            )
//        );

        $orderIds = Mage::app()->getRequest()->getParam('internal_order_ids',null);
        if ($orderIds){
            $orderIds = explode(',',$orderIds);
            $collection->addAttributeToFilter('entity_id',array('in'=>$orderIds));
        }

        $collection->getSelect()->group('main_table.entity_id');

//        $collection->getSelect()->joinLeft(array('sfog' => 'sales_flat_order_grid'),
//            'main_table.entity_id = sfog.entity_id',array('sfog.shipping_name','sfog.billing_name'));
//
//        $collection->getSelect()->joinLeft(array('sfo'=>'sales_flat_order'),
//            'sfo.entity_id=main_table.entity_id',array('sfo.customer_email','sfo.weight',
//                                                       'sfo.discount_description','sfo.increment_id','sfo.store_id','sfo.created_at','sfo.status',
//                                                       'sfo.base_grand_total','sfo.grand_total'));

        $collection->getSelect()->joinLeft(array('shipping_t'=>'sales_flat_order_address'),
            'main_table.entity_id = shipping_t.parent_id AND shipping_t.address_type="shipping"',
            array(
                'shipping_street'       => 'shipping_t.street',
                'shipping_city'         => 'shipping_t.city',
                'shipping_region'       => 'shipping_t.region',
                'shipping_postcode'     => 'shipping_t.postcode',
                'shipping_country'     => 'shipping_t.country_id',
                'shipping_telephone'    => 'shipping_t.telephone',
                'shipping_company'    => 'shipping_t.company',
                'shipping_firstname'    => 'shipping_t.firstname',
                'shipping_lastname'    => 'shipping_t.lastname',
            ));

        $collection->getSelect()->joinLeft(array('billing_t'=>'sales_flat_order_address'),
            'main_table.entity_id = billing_t.parent_id AND billing_t.address_type="billing"',
            array(
                'billing_street'       => 'billing_t.street',
                'billing_city'         => 'billing_t.city',
                'billing_region'       => 'billing_t.region',
                'billing_postcode'     => 'billing_t.postcode',
                'billing_country'      => 'billing_t.country_id',
                'billing_telephone'    => 'billing_t.telephone',
                'billing_company'      => 'billing_t.company',
                'billing_firstname'    => 'billing_t.firstname',
                'billing_lastname'    => 'billing_t.lastname',
            ));

//        Mage::log($collection->getFirstItem()->debug(), null, 'debug_a.log');
//        Mage::log($collection->getFirstItem()->getData(), null, 'debug_a.log');
//        die('!!!');

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('real_order_id', array(
            'header'=> Mage::helper('sales')->__('Order #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'increment_id',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => Mage::helper('sales')->__('Purchased From (Store)'),
                'index'     => 'store_id',
                'type'      => 'store',
                'store_view'=> true,
                'display_deleted' => true,
            ));
        }

        $this->addColumn('created_at', array(
            'header' => Mage::helper('sales')->__('Purchased On'),
            'index' => 'created_at',
            'type'      => 'datetime',
            'align'     => 'right',
            'format'    => 'M/d/yy h:m',
            'width'     => '90px',
        ));

        $this->addColumn('base_grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Base)'),
            'index' => 'base_grand_total',
            'type'  => 'currency',
            'currency' => 'base_currency_code',
        ));

        $this->addColumn('grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Purchased)'),
            'index' => 'grand_total',
            'type'  => 'currency',
            'currency' => 'order_currency_code',
        ));

//         $this->addColumn('cost', array(
//            'header' => Mage::helper('sales')->__('cost'),
//            'index' => 'subtotal',
//            'type'  => 'currency',
//            'currency' => 'base_currency_code',
//            'renderer' => new Oggetto_Wms_Block_Adminhtml_Sales_Order_Renderer_Cost()
//        ));
        $this->addColumn('cost', array(
            'header' => Mage::helper('sales')->__('cost'),
            'index' => 'subtotal',
            'type'  => 'currency',
            'currency' => 'base_currency_code',
//            'renderer' => new Oggetto_Wms_Block_Adminhtml_Sales_Order_Renderer_Cost()
        ));

        $this->addColumn('subtotal', array(
            'header' => Mage::helper('sales')->__('subtotal'),
            'index' => 'subtotal',
            'type'  => 'currency',
            'currency' => 'base_currency_code',
        ));

        $this->addColumn('discount_code', array(
            'header' => Mage::helper('sales')->__('Discount Code'),
//            'index' => 'discount_code',
            'index' => 'coupon_code',
//            'type'  => 'currency',
//            'currency' => 'coupon_code',
        ));

        $this->addColumn('discount_amount', array(
            'header' => Mage::helper('sales')->__('Discount Amount'),
            'index' => 'discount_amount',
            'type'  => 'currency',
//            'renderer' => new Oggetto_Wms_Block_Adminhtml_Sales_Order_Renderer_DiscountAmount()
        ));

        $this->addColumn('tax_amount', array(
            'header' => Mage::helper('sales')->__('tax amount'),
            'index' => 'tax_amount',
            'type'  => 'currency',
            'currency' => 'base_currency_code',
        ));

        $this->addColumn('shipping_amount', array(
            'header' => Mage::helper('sales')->__('shipping amount'),
            'index' => 'shipping_amount',
            'type'  => 'currency',
            'currency' => 'base_currency_code',
        ));

        $this->addColumn('total_refunded', array(
            'header' => Mage::helper('sales')->__('Total Refunded'),
            'index' => 'total_refunded',
            'renderer' => new SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_TotalRefunded()
        ));

        $this->addColumn('customer_name', array(
            'header' => Mage::helper('sales')->__('Customer Name'),
            'index' => 'customer_name',
            'renderer' => new SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_CustomerName()
        ));

        $this->addColumn('customer_email', array(
            'header' => Mage::helper('sales')->__('Email'),
            'index' => 'customer_email',
            'type'  => 'text',
//            'renderer' => new Oggetto_Wms_Block_Adminhtml_Sales_Order_Renderer_CustomerEmail()
        ));

        $this->addColumn('customer_group', array(
            'header' => Mage::helper('sales')->__('Customer Group'),
            'index' => 'customer_group',
            'renderer' => new SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_CustomerGroup()
        ));

//        $this->addColumn('account_executive',array(
//            'header'=> Mage::helper('sales')->__('Account Executive'),
//            'width' => '40px',
//            'type'  => 'text',
//            'renderer' => new SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_Executive(),
//        ));

//        $this->addColumn('payment_terms',array(
//            'header'=> Mage::helper('sales')->__('Payment Terms'),
//            'width' => '40px',
//            'type'  => 'text',
//            'renderer' => new SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_Payment(),
//        ));

        $this->addColumn('payment_method', array(
            'header' => Mage::helper('sales')->__('Payment Method'),
            'index' => 'payment_method',
            'renderer' => new SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_PaymentMethod()
        ));

        $this->addColumn('shipping_method', array(
            'header' => Mage::helper('sales')->__('Shipping Method'),
            'index' => 'shipping_description',
            'renderer' => new SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_ShippingMethod()
        ));

        $this->addColumn('shipping_name', array(
            'header' => Mage::helper('sales')->__('Shipping Name'),
            'index' => 'shipping_name',
            'renderer' => new SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_ShippingName()
        ));

        $this->addColumn('billing_name', array(
            'header' => Mage::helper('sales')->__('Billing Name'),
            'index' => 'billing_name',
            'renderer' => new SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_BillingName()
        ));

        $this->addColumn('shipping_1', array(
            'header' => Mage::helper('sales')->__('Shipping Address'),
            'index' => 'shipping_address',
            'renderer' => new SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_Shipping()
        ));

        $this->addColumn('shipping_company', array(
            'header' => Mage::helper('sales')->__('company'),
            'index' => 'shipping_company',
//            'renderer' => new Oggetto_Wms_Block_Adminhtml_Sales_Order_Renderer_Shippingdata()
        ));


//        $this->addColumn('street', array(
//            'header' => Mage::helper('sales')->__('street'),
//            'index' => 'street',
//            'renderer' => new Oggetto_Wms_Block_Adminhtml_Sales_Order_Renderer_Shippingdata()
//        ));
        $this->addColumn('shipping_street', array(
            'header' => Mage::helper('sales')->__('street'),
            'index' => 'shipping_street',
            'type'  => 'text',
        ));

        $this->addColumn('shipping_city', array(
            'header' => Mage::helper('sales')->__('city'),
            'index' => 'shipping_city',
//            'renderer' => new Oggetto_Wms_Block_Adminhtml_Sales_Order_Renderer_Shippingdata()
        ));

        $this->addColumn('shipping_region', array(
            'header' => Mage::helper('sales')->__('region'),
            'index' => 'shipping_region',
//            'renderer' => new Oggetto_Wms_Block_Adminhtml_Sales_Order_Renderer_Shippingdata()
        ));


        $this->addColumn('shipping_postcode', array(
            'header' => Mage::helper('sales')->__('postcode'),
            'index' => 'shipping_postcode',
//            'renderer' => new Oggetto_Wms_Block_Adminhtml_Sales_Order_Renderer_Shippingdata()
        ));

        $this->addColumn('shipping_country', array(
            'header' => Mage::helper('sales')->__('country'),
            'index' => 'shipping_country',
//            'renderer' => new Oggetto_Wms_Block_Adminhtml_Sales_Order_Renderer_Shippingdata()
        ));


        $this->addColumn('shipping_telephone', array(
            'header' => Mage::helper('sales')->__('telephone'),
            'index' => 'shipping_telephone',
//            'renderer' => new Oggetto_Wms_Block_Adminhtml_Sales_Order_Renderer_Shippingdata()
        ));


        /* Billing adress block */


        $this->addColumn('billing_company', array(
            'header' => Mage::helper('sales')->__('billing_company'),
            'index' => 'billing_company',
//            'renderer' => new Oggetto_Wms_Block_Adminhtml_Sales_Order_Renderer_Billingdata()
        ));


        $this->addColumn('billing_street', array(
            'header' => Mage::helper('sales')->__('billing_street'),
            'index' => 'billing_street',
//            'renderer' => new Oggetto_Wms_Block_Adminhtml_Sales_Order_Renderer_Billingdata()
        ));

        $this->addColumn('billing_city', array(
            'header' => Mage::helper('sales')->__('billing_city'),
            'index' => 'billing_city',
//            'renderer' => new Oggetto_Wms_Block_Adminhtml_Sales_Order_Renderer_Billingdata()
        ));

        $this->addColumn('billing_region', array(
            'header' => Mage::helper('sales')->__('billing_region'),
            'index' => 'billing_region',
//            'renderer' => new Oggetto_Wms_Block_Adminhtml_Sales_Order_Renderer_Billingdata()
        ));


        $this->addColumn('billing_postcode', array(
            'header' => Mage::helper('sales')->__('billing_postcode'),
            'index' => 'billing_postcode',
//            'renderer' => new Oggetto_Wms_Block_Adminhtml_Sales_Order_Renderer_Billingdata()
        ));

        $this->addColumn('billing_country', array(
            'header' => Mage::helper('sales')->__('billing_country'),
            'index' => 'billing_country',
//            'renderer' => new Oggetto_Wms_Block_Adminhtml_Sales_Order_Renderer_Billingdata()
        ));


        $this->addColumn('billing_telephone', array(
            'header' => Mage::helper('sales')->__('telephone'),
            'index' => 'billing_telephone',
//            'renderer' => new Oggetto_Wms_Block_Adminhtml_Sales_Order_Renderer_Billingdata()
        ));

        /* End Billing Adress Block*/

        $this->addColumn('total_qty_ordered', array(
            'header' => Mage::helper('sales')->__('qty_trucks'),
//            'index' => 'total_qty_ordered',
//            'type'  => 'number'
            'index' => 'qty_trucks',
            'renderer' => new SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_Items()
        ));

        $this->addColumn('qty_gift', array(
            'header' => Mage::helper('sales')->__('qty_gift'),
            'index' => 'qty_gift',
            'renderer' => new SLV_SalesReport_Block_Adminhtml_Sales_Order_Renderer_Gifts()
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('sales')->__('Status'),
            'index' => 'status',
            'type'  => 'options',
            'width' => '70px',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));

        return $this;
    }

    /**
     * Retrieve Grid data as CSV
     *
     * @return string
     */
    public function getCsv($addHeader = true)
    {
        $csv = '';
        $this->_isExport = true;
        $this->_prepareGrid();
        $this->getCollection()->getSelect()->limit();
        $this->getCollection()->setPageSize(0);
        $this->getCollection()->load();
        $this->_afterLoadCollection();

        if ($addHeader){
            $data = array();
            foreach ($this->_columns as $column) {
                if (!$column->getIsSystem()) {
                    $data[] = '"'.$column->getExportHeader().'"';
                }
            }
            $csv.= implode(',', $data)."\n";
        }


        foreach ($this->getCollection() as $item) {
            $data = array();
            foreach ($this->_columns as $column) {
                if (!$column->getIsSystem()) {
                    $data[] = '"' . str_replace(array('"', '\\'), array('""', '\\\\'),
                            $column->getRowFieldExport($item)) . '"';
                }
            }
            $csv.= implode(',', $data)."\n";
        }

        if ($this->getCountTotals())
        {
            $data = array();
            foreach ($this->_columns as $column) {
                if (!$column->getIsSystem()) {
                    $data[] = '"' . str_replace(array('"', '\\'), array('""', '\\\\'),
                            $column->getRowFieldExport($this->getTotals())) . '"';
                }
            }
            $csv.= implode(',', $data)."\n";
        }

        return $csv;
    }

}
