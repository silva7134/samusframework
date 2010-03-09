<?php



class TesteController extends Samus_Controller {

    public function index() {
        echo "Sou um teste";
    }
    
    public function testeAction($parametro) {
        echo "<h1>Executei Teste $parametro</h1>";
    }

}


?>
