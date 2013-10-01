<?php

require_once 'Mage/Adminhtml/controllers/Sales/OrderController.php';
class SLV_SalesReport_Adminhtml_Sales_OrderController extends Mage_Adminhtml_Sales_OrderController
{

    public function exportMoreDataCsvAction()
    {
        $fileName = 'orders.csv';
        $grid = $this->getLayout()->createBlock('adminhtml/sales_order_gridall');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    public function exportOrdersReportAction()
    {
        set_time_limit(1800);
        ini_set('memory_limit', '1024M');
        $fileName = 'orders.csv';
        $grid = $this->getLayout()->createBlock('slv_salesreport/adminhtml_sales_order_gridall2');

        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }
}
