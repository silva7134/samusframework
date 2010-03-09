<?php

require_once ('samus/Samus_Model.php');
require_once 'models/conteudo/ConteudoEnquete.php';

class ConteudoEnqueteResposta extends Samus_Model {
	
	/**
	 * Pergunta da enquete Correspondete
	 *
	 * @var ConteudoEnquete INTEGER
	 */
	protected $conteudoEnquete;
	
	/**
	 * Resposta da enquete
	 *
	 * @var string VARCHAR(200)
	 */
	protected $resposta;
	
	/**
	 * Numero de votos
	 *
	 * @var int INTEGER NOT NULL 
	 */
	protected $votos;
	
	/**
	 * @return ConteudoEnquete
	 */
	public function getConteudoEnquete() {
		return $this->conteudoEnquete;
	}
	
	/**
	 * @return string
	 */
	public function getResposta() {
		return $this->resposta;
	}
	
	/**
	 * @return int
	 */
	public function getVotos() {
		return $this->votos;
	}
	
	/**
	 * @param ConteudoEnquete $conteudoEnquete
	 */
	public function setConteudoEnquete($conteudoEnquete) {
		$this->conteudoEnquete = $conteudoEnquete;
	}
	
	/**
	 * @param string $resposta
	 */
	public function setResposta($resposta) {
		$this->resposta = $resposta;
	}
	
	/**
	 * @param int $votos
	 */
	public function setVotos($votos) {
		$this->votos = $votos;
	}

    /**
     * Casting
     * @param ConteudoEnqueteResposta $object
     * @return ConteudoEnqueteResposta
     */
    public static function cast( $object) {
        return  $object;
    }
	
}

?>