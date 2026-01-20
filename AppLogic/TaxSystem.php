<?php
class TaxSystem {
    private $taxRules = ["standard" => 0.15, "reduced" => 0.05];

    public function getTaxRules() {
        return $this->taxRules;
    }

    public function setTaxRules($taxRules) {
        $this->taxRules = $taxRules;
    }

    public function calculateTax($amount, $category = "standard") {
        $rate = isset($this->taxRules[$category]) ? $this->taxRules[$category] : $this->taxRules["standard"];
        return $amount * $rate;
    }
}
?>