<?php

class MetodoTestController extends Samus_Controller {
	
    public $msg;
    
    public function index() {
	    echo '<hr>Eu sou um teste';
	}
	
	public function vamosAction() {
	    $this->setTemplateFile("hello/vamos");
	    $this->msg = '<hr>Executei o VAMOSAction!';
	}
	
	public function legalAction() {
	    echo '<hr>Executei o LegalAction';
	}
	
	public function novaAcaoAction($parametro1, $parametro2) {
	    echo "<hr>Executei o novaAcaoAction Parametro1: $parametro1 Parametro2: $parametro2";
	}
	
	public function sayHelloAction($comprimento , $nome="Samus") {
	    echo "<h1>$comprimento, $nome</h1>";
	    echo $_GET['id'];
	}
	
	private function ilegalAction() {
	    echo "Esse metodo naum pode ser executado";
	}
	
}

?>