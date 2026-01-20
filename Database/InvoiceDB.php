<?php
class InvoiceDB {
    private $connection;
    private $tableName = "invoices";
    private $invoiceCounter = 1000;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function setConnection($connection) {
        $this->connection = $connection;
    }

    public function getTableName() {
        return $this->tableName;
    }

    public function setTableName($tableName) {
        $this->tableName = $tableName;
    }

    public function getInvoiceCounter() {
        return $this->invoiceCounter;
    }

    public function setInvoiceCounter($invoiceCounter) {
        $this->invoiceCounter = $invoiceCounter;
    }

    public function saveInvoice($invoiceData) {
        $this->invoiceCounter++;
        $invoiceId = $this->invoiceCounter;

        error_log("[InvoiceDB] Invoice saved with ID: $invoiceId");
        
        return $invoiceId;
    }
}
?>