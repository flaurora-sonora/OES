<?php
class ISBN {
    public $check;
    public $checkDigit;
    public $hyphens;
    public $translate;
    public $validation;
    public function __construct() {
        include('Hyphens.php');
		$this->hyphens = new Hyphens();
        include('check.php');
		$this->check = new Check($this->hyphens);
        include('CheckDigit.php');
		$this->checkDigit = new CheckDigit($this->hyphens);
        include('Translate.php');
		$this->translate = new Translate($this->checkDigit);
        include('Validation.php');
		$this->validation = new Validation($this->check, $this->hyphens);
    }
}
