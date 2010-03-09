<?php

/**
 * Hello World no SamusFramework
 *
 * @author samus
 */
class HelloWorldController extends Samus_Controller {

    public $hello;

    public function index() {
        

        $this->hello = "Hello World! Hoje é ".date("d/m/y - H:i:s");

    }

}
?>