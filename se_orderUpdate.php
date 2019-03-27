#!/usr/local/bin/ea-php56
<?php

class OrderUpdate
{
    const APP = '/home/se/public_html/app/Mage.php';
    const BASEPATH = '/home/se/se_orderUpdate';
    const ARCHIVE = 'archive';
    const INCOMING = 'incoming';
    const STORECODE = 'se';
    const NOTIFYCUSTOMER = true;
    const INCLUDECOMMENT = false;
    const COMMENT = 'Updated Programmatically';
    const INVOICE_PDF = 'invoice_pdf';

    protected $app;
    protected $path;
    protected $opts;
    protected $store;
    protected $archivePath;
    protected $incomingPath;
    protected $storeCode;
    protected $file;

    public function __construct()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        require_once self::APP;
        $this->app = Mage::app('admin');
        $this->storeCode = $this->getOpts();
        $this->incomingPath = self::BASEPATH . DS . self::INCOMING;
        $this->archivePath = self::BASEPATH . DS . self::ARCHIVE;
        
        $result = $this->getFiles();

        if ($result) {
            if (!is_dir($this->archivePath)) {
                mkdir($this->archivePath, 0777, true);
            }
            rename($this->incomingPath . DS . $this->file, $this->archivePath . DS . $this->file);
            $this->_success('All Orders Updated Successfully.'); 
        } else {
            $this->_fault("There were issues updating orders, please check logs");
        }
    }

    protected function readCsv($file)
    {
        $keys;
        $values = array();
        if (!is_array($file)) {
            $abspath = $this->incomingPath . DS . $file;
            $ext = pathinfo($abspath, PATHINFO_EXTENSION);
            $row = 1;
            if ($ext = 'csv') {
                if (($fp = fopen($abspath, "r")) !== FALSE) {
                    while (($data = fgetcsv($fp, 4096, ",")) !== FALSE) {
                        $num = count($data);
                        if ($row === 1) {
				$bom = pack('H*', 'EFBBBF');
				$data = preg_replace("/$bom/", '', $data);
				$keys = $data;
                        } else {
                            $values[] = $data;
                        }
                        $row++;
                    }
                    fclose($fp);
                }
            }
        } 

        $result = $this->update($keys, $values);
        
        return $result;
    }

    protected function getFiles()
    {
        if (is_dir($this->incomingPath)) {
            $files = scandir($this->incomingPath);
            foreach($files as $file) {
                if ($file != '.' && $file != '..') {
                    $this->file = $file;
                    $result = $this->readCsv($file);
                }
            }
        }

        return $result;
    }

    protected function getOpts()
    {
        $storeCode = isset($argv[1]) ? $argv[1] : self::STORECODE;

        $store = Mage::getModel("core/store")->load($storeCode);

        if (empty($store) || !$store->getId()) {
            $this->_fault($storeCode . ' store does not exist.');
            return false;
        }

        return $storeCode;
    }

    protected function update($keys, $values) {
        $updates = array();
        
        for ($i = 0; $i < count($values); $i++) {
            $updates[$i] = array_combine($keys, $values[$i]);
        }

        foreach ($updates as $update) {
            $orderId = $update['orderId'];
            $order = Mage::getModel('sales/order')
                ->loadByIncrementId($orderId);

            if (!$order->getId()) {
                $this->_fault('No Order', $orderId);
                return false;
            }

            $tracking = $update['trackingNumber'];
            $method = $update['method'];
            $captureAmount = $update['capture_amount'];
            $itemqty = $update['itemId_qty'];
            $partialCaptureFlag = $update['partial_capture'];

            try {
                // Create Shipment
                if($order->canShip()) {
                    echo "creating shipment...\n";
                    $converter = Mage::getModel('sales/convert_order');
                    $shipment = $converter->toShipment($order);
                
                    foreach ($order->getAllItems() as $orderItem) {
                        if (!$orderItem->getQtyToShip()) {
                            continue;
                        }
                        if ($orderItem->getIsVirtual()) {
                            continue;
                        }

                        $item = $converter->itemToShipmentItem($orderItem);
                        $qty = $orderItem->getQtyToShip();
                        $item->setQty($qty);
                        $shipment->addItem($item);
                    }

                    $track = Mage::getModel('sales/order_shipment_track')
                        ->setNumber($tracking);						
                    $track->setCarrierCode('custom');
                    $track->setTitle($method);

                    $shipment->addTrack($track);
        
                    $email = $order->getCustomerEmail();
                    $shipment->register();
                    $shipment->setEmailSent(true);
                    $shipment->getOrder()->setIsInProcess(true);

                    $transactionSave = Mage::getModel('core/resource_transaction')
                        ->addObject($shipment)
                        ->addObject($shipment->getOrder())
                        ->save();

                    $shipment->sendEmail($email, (self::INCLUDECOMMENT ? self::COMMENT : ''));
                    $shipment->save();
                }

                // Create Invoice
                if ($order->canInvoice()) {
                    $qtys = array();
                    $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice($qtys);
                    echo "creating invoice\n";
                
                    $invoice->register();

                    if ($invoice->getState() != Mage_Sales_Model_Order_Invoice::STATE_PAID) {
                        $invoice->pay();
                    }
                
                    $invoice->getOrder()->setIsInProcess(true);
                
                    $transactionSave = Mage::getModel('core/resource_transaction')
                        ->addObject($invoice)
                        ->addObject($order)
                        ->save();
                
                    $invoicePdfFile = $update['invoice_pdf'];

                    if ($invoicePdfFile != '') {
                        $invoicePdfDir = $baseDir . DS . self::INVOICE_PDF;

                        if (!is_dir($invoicePdfDir)) {
                            mkdir($invoicePdfDir, 0777, true);
                        }

                        $invoicePdfFilePath = $invoicePdfDir . DS . $invoicePdfFile;

                        $resource = Mage::getSingleton('core/resource');
                        $writeConnection = $resource->getConnection('core_write');

                        if ($order->hasInvoices()) {
                            $invoiceIncrementId = '';
                            foreach ($order->getInvoiceCollection() as $inv) {
                                $invoiceIncrementId = $inv->getIncrementId();
                            }
                        }

                        $table = $resource->getTableName('sales/invoice');
                        $query = "UPDATE {$table} SET invoice_pdf = '{$invoicePdfFilePath}' WHERE increment_id = " . (int)$invoiceIncrementId;
                        $writeConnection->query($query);

                    }
                } else {
                    $this->_fault("Invoice already created for this order");
                    return false;
                }

                // Capture 
                if ((bool) $invoice) {
                    $order->getPayment()->capture($invoice);
                    $this->_success('Full Capture Done.');
                } else {
                    $this->_fault('Cannot Capture Order');
                    return false;
                }

                $order->setStatus('Complete');
                $order->addStatusToHistory($order->getStatus(), SELF::COMMENT, false);
                $order->save();

                $this->_success("Order Status Update Complete");
            
                if ($captureAmount != null && $captureAmount != "") {
                    $orderData = sprintf("%s,%s,%s,%s\r\n", $orderId, $tracking, $captureAmount, "order updated!");
                } else {
                    $orderData = sprintf("%s,%s,%s\r\n", $orderId, $tracking, "order updated!");
                }
            } catch(Mage_Core_Exception $e){
                $this->_fault("Error processing order update.", $e->getMessage());
                return false;
            }
        }

        $this->_success($orderData);
        return true;
    }

    protected function _fault($string, $msg = null)
    {
        Mage::log($string . ' ' . $msg, null, 'orderUpdate.log');
        echo $string . ' ' . $msg . "\n";
    }

    protected function _success($string, $msg = null)
    {
        Mage::log($string . ' ' . $msg . null, 'orderUpdate.log');
        echo $string . ' ' . $msg . "\n";
    }
}

$app = new OrderUpdate();
