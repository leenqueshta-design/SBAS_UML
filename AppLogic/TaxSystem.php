<?php
class TaxSystem {
    private $taxRules = ["standard" => 0.15, "reduced" => 0.05];
    private $exemptCategories = ["books", "food"];

    public function getTaxRules() {
        return $this->taxRules;
    }

    public function setTaxRules($taxRules) {
        $this->taxRules = $taxRules;
    }

    public function getExemptCategories() {
        return $this->exemptCategories;
    }

    public function setExemptCategories($exemptCategories) {
        $this->exemptCategories = $exemptCategories;
    }

    public function calculateTax($amount, $category = "standard") {
        if (in_array($category, $this->exemptCategories)) {
            return 0;
        }
        
        $rate = isset($this->taxRules[$category]) ? $this->taxRules[$category] : $this->taxRules["standard"];
        return $amount * $rate;
    }
}

?>
