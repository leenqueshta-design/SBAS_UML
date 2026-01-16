<?php
require_once __DIR__ . '/TaxSystem.php';
require_once __DIR__ . '/../Database/InvoiceDB.php';

class InvoiceManager {
    private $taxSystem;
    private $invoiceDB;
    private $validator = null;

    public function __construct($taxSystem, $invoiceDB) {
        $this->taxSystem = $taxSystem;
        $this->invoiceDB = $invoiceDB;
    }

    public function getTaxSystem() {
        return $this->taxSystem;
    }

    public function setTaxSystem($taxSystem) {
        $this->taxSystem = $taxSystem;
    }

    public function getInvoiceDB() {
        return $this->invoiceDB;
    }

    public function setInvoiceDB($invoiceDB) {
        $this->invoiceDB = $invoiceDB;
    }

    public function getValidator() {
        return $this->validator;
    }

    public function setValidator($validator) {
        $this->validator = $validator;
    }

    public function createInvoice($customerId, $items) {
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item["price"] * $item["quantity"];
        }

        $taxAmount = $this->taxSystem->calculateTax($subtotal);
        $total = $subtotal + $taxAmount;

        $invoiceData = [
            "customer_id" => $customerId,
            "items" => $items,
            "subtotal" => $subtotal,
            "tax" => $taxAmount,
            "total" => $total,
            "created_at" => date("Y-m-d H:i:s")
        ];

        $invoiceId = $this->invoiceDB->saveInvoice($invoiceData);
        return [$invoiceId, $invoiceData];
    }
}
?>
