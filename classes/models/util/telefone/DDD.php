<?php
 


class DDD extends Samus_Model {

	/**
	 * Codigo DDD 
	 *
	 * @var int INTEGER
	 */
	protected $cod;
	
	/**
	 * Estado que possui este DDD
	 *
	 * @var Estado INTEGER
	 */
	protected $estado;

	
	/**
	 * @return int
	 */
	public function getCod() {
		return $this->cod;
	}

	/**
	 * @param int $cod
	 */
	public function setCod($cod) {
		$this->cod = $cod;
	}

	
	/**
	 * @return Estado
	 */
	public function getEstado() {
		return $this->estado;
	}

	/**
	 * @param Estado $estado
	 */
	public function setEstado($estado) {
		$this->estado = $estado;
	}

	
}


?>