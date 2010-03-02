<?php
require_once 'samus/Samus_AdminInterface.php';

/**
 * Samus_Admin -
 * Classe responsбvel pela criaзгo de base de cуdigos HTML para agializar o
 * desenvolvimento de Admins.
 *
 * @author Vinicius Fiorio Custуdio - samusdev@gmail.com
 */
class Samus_Admin extends Samus_Object implements Samus_AdminInterface {

/**
 * Nome da classe analisada
 * @var string
 */
    private $className;

    public function __construct($className) {
        $this->setClassName($className);
    }

    public static function analiseClass() {

    }

    public function generateAdmin() {
        $ref = new ReflectionClass($this->getClassName());

        $properties = $ref->getProperties();

        $str = "";

        foreach ($properties as $propriedade ) {

            $doc = $propriedade->getDocComment();

            if (! empty($doc)) {
                $doc = strstr($doc , "@var");
                if (! empty($doc)) {

                    $doc = str_replace("/" , "" , $doc);
                    $doc = str_replace("*" , "" , $doc);
                    $doc = str_replace("@var" , "" , $doc);
                    $doc = trim($doc);

                    $parametrosArray = split(" " , $doc);
                    array_unshift($parametrosArray , $propriedade->getName());

                    var_dump($parametrosArray);

                    $dbColumns[] = $parametrosArray;

                }
            }


        }

    }



    /**
     * Obtem o nome da classe analisada
     * @return string
     */
    public function getClassName() {
        return $this->className;
    }

    /**
     * Especifica o nome da classe que serб analisada
     * @param $className string
     */
    public function setClassName($className) {
        $this->className = $className;
    }

}


?>