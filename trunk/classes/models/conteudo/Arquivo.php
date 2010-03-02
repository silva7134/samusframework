<?php

/**
 * Arquivos para download
 *
 * @name arquivos
 */
class Arquivo extends Samus_Model {
	
	/**
	 * Nome do arquivo armazenado no banco
	 *
	 * @var string VARCHAR(25) NOT NULL
	 */
	protected $arquivo;
	
	/**
	 * Nome original do arquivo submetido pelo usuсrio
	 *
	 * @var string VARCHAR(120)
	 */
	protected $nomeOriginal;
	
	
	/**
	 * Descriчуo do arquivo postado
	 *
	 * @var string VARCHAR(250)
	 */
	protected $descricao;
	
	
	/**
	 * Pasta do arquivo (as pastas agrupam os arquivos das pсginas)
	 *
	 * @var ArquivoPasta INTEGER(10)
	 */
	protected $arquivoPasta;

	
	public function __construct($id="") {
		require_once 'models/conteudo/ArquivoPasta.php';
		parent::__construct($id);	
	}
	
	public function getFile() {
		return  self::getDir() . $this->arquivo;
	}
	
	/**
	 * @return string
	 */
	public function getArquivo() {
		return $this->arquivo;
	}

	/**
	 * @return ArquivoPasta
	 */
	public function getArquivoPasta() {
		return $this->arquivoPasta;
	}

	/**
	 * @return string
	 */
	public function getDescricao() {
		return $this->descricao;
	}

	/**
	 * @return string
	 */
	public function getNomeOriginal() {
		return $this->nomeOriginal;
	}

	/**
	 * @param string $arquivo
	 */
	public function setArquivo($arquivo) {
		$this->arquivo = $arquivo;
	}

	/**
	 * @param ArquivoPasta $arquivoPasta
	 */
	public function setArquivoPasta(ArquivoPasta $arquivoPasta) {
		$this->arquivoPasta = $arquivoPasta;
	}

	/**
	 * @param string $descricao
	 */
	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}

	/**
	 * @param string $nomeOriginal
	 */
	public function setNomeOriginal($nomeOriginal) {
		$this->nomeOriginal = $nomeOriginal;
	}
	
	public static function getDir() {
		return PROJETO_DIR . 'files/anexos/';
	}

	
}


?>