<?php
require_once 'models/conteudo/Arquivo.php';

/**
 * Classes associativa entre os arquivos e suas paginas
 * @name arquivos_pastas
 */
class ArquivoPasta extends Samus_Model {

	/**
	 * Nome da pasta
	 *
	 * @var string VARCHAR(75)
	 */
	protected $nome;
	
	/**
	 * Descriчуo da pasta criada
	 *
	 * @var string VARCHAR(250)
	 */
	protected $descricao;

	protected $arquivos = array();

	public function __construct($id="") {
		parent::__construct($id);	
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
	public function getNome() {
		return $this->nome;
	}

	/**
	 * @param string $descricao
	 */
	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}

	/**
	 * @param string $nome
	 */
	public function setNome($nome) {
		$this->nome = $nome;
	}

	
	/**
	 * @return array
	 */
	public function getArquivos() {
		return $this->arquivos;
	}

	/**
	 * @param array $arquivos
	 */
	public function setArquivos($arquivos) {
		$this->arquivos = $arquivos;
	}

	
}


?>