<?php

/**
 * Interface que especifíca todos os métodos que os controladores devem implementar
 * @author Vinicius Fiorio Custódio
 * @package Samus
 */
interface Samus_ControllerInterface {

	/**
	 * Método que é chamado para iniciar qualquer controle
	 *
	 */
	public function index();

	/**
	 * Este método é responsável pela ponte entre o Controlador e o Template
	 * associado, é sempre executado depois da chamada ao método index
	 *
	 * @param string $directory 
	 * @param string $metodo
	 * @param array $args
	 */
	public function assignClass($directory="" , $metodo="" ,array $args = array());

}

?>