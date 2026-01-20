<?php
class InvoiceForm {
    private $customerComboBox = "";
    private $productTable = [];
    private $saveButton = "Save Invoice";
    private $totalLabel = "0.00";

    public function getCustomerComboBox() {
        return $this->customerComboBox;
    }

    public function setCustomerComboBox($customerId) {
        $this->customerComboBox = $customerId;
    }

    public function getProductTable() {
        return $this->productTable;
    }

    public function setProductTable($products) {
        $this->productTable = $products;
    }

    public function getSaveButton() {
        return $this->saveButton;
    }

    public function setSaveButton($buttonText) {
        $this->saveButton = $buttonText;
    }

    public function getTotalLabel() {
        return $this->totalLabel;
    }

    public function setTotalLabel($total) {
        $this->totalLabel = number_format($total, 2);
    }

    public function addItem($productName, $price, $quantity) {
        $this->productTable[] = [
            "name" => $productName,
            "price" => $price,
            "quantity" => $quantity
        ];
    }

    public function createInvoice($invoiceManager) {
        if (empty($this->customerComboBox)) {
            return [false, "Please select a customer"];
        }
        
        if (empty($this->productTable)) {
            return [false, "No items added"];
        }

        list($invoiceId, $invoiceData) = $invoiceManager->createInvoice(
            $this->customerComboBox,
            $this->productTable
        );
        
        $this->totalLabel = number_format($invoiceData["total"], 2);
        return [true, "Invoice created successfully", $invoiceId, $invoiceData];
    }

    public function render() {
        $html = '<div class="invoice-form">';
        $html .= '<h2>Create New Invoice</h2>';
        
        $html .= '<form method="POST">';
        $html .= '<select name="customer_id">';
        $html .= '<option value="">Select Customer</option>';
        $html .= '<option value="CUST-001"' . ($this->customerComboBox == "CUST-001" ? " selected" : "") . '>Customer 1</option>';
        $html .= '<option value="CUST-002"' . ($this->customerComboBox == "CUST-002" ? " selected" : "") . '>Customer 2</option>';
        $html .= '</select>';
        
        $html .= '<div class="items">';
        foreach ($this->productTable as $index => $item) {
            $html .= '<div>Item: ' . $item["name"] . ' - $' . $item["price"] . ' x ' . $item["quantity"] . '</div>';
        }
        $html .= '</div>';
        
        $html .= '<div class="total">Total: $' . $this->totalLabel . '</div>';
        $html .= '<button type="submit" name="save_invoice">' . $this->saveButton . '</button>';
        $html .= '</form>';
        $html .= '</div>';
        
        return $html;
    }
}
?>