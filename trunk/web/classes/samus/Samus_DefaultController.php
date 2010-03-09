<?php
require_once ('samus/Samus_Controller.php');
require_once 'samus/Samus_Model.php';
/**
 * Esta classe é invocada quando uma views sem controlador é invocadas
 *
 * @package Samus
 * @author Vinicius Fiorio Custodio - Samusdev@gmail.com
 *
 */
class Samus_DefaultController extends Samus_Controller {

    public function index() {

    }

	/**
	 * @see Samus_Controller::assignClass()
	 *
	 * @param string $directory nome do arquivo de template sem a extensão
	 * @param string $metodo
	 * @param array $args
	 */
    public function assignClass($directory = "", $metodo = "", array $args = array()) {

	$ref = new ReflectionClass( $this );

	if (! empty( $metodo )) {
			/*@var $refMetodo ReflectionMethod*/
	    $refMetodo = $ref->getMethod( $metodo );
	    $refMetodo->invoke( $this , $args );
	}

	$ref = new ReflectionObject( $this );
	$met = $ref->getMethods();
	$ai = new ArrayIterator( $met );
	$minhaArray = array ();
	while ( $ai->valid() ) {

	    $metName = $ai->current()->getName();

	    if (substr( $metName , 0 , 3 ) == "get" && $metName {3} != "_") {
		$prop = substr( $metName , 3 );
		$prop = strtolower( substr( $prop , 0 , 1 ) ) . substr( $prop , 1 );

		$minhaArray [$prop] = $ai->current()->invoke( $this );

				/**
				 * Atribui para a classe de visão todas as propriedades do
				 * objeto depois de executar o método especificado (aki é a
				 * chave do negócio)
				 */
		$this->smarty->assign( $prop , $ai->current()->invoke( $this ) );

	    }

	    $ai->next();
	}

	$properties = $ref->getProperties();

	foreach ( $properties as $prop ) {
			/*@var $prop ReflectionProperty */
	    if ($prop->isPublic()) {
		$this->smarty->assign( $prop->getName() , $prop->getValue( $this ) );
	    }
	}


	$directory = str_replace(Samus::getViewsDirectory()."/", "", $directory);

	$this->smarty->display( $directory );


    }

}

?>