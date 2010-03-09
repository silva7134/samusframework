<?php
require_once 'models/conteudo/PaginaCategoria.php';
/**
 * Categoria de conteudos divididos em categorias
 * @name categorias
 */
class Categoria extends Samus_Model {

/**
 * Nome da categoria
 *
 * @var string VARCHAR(75)
 */
    protected $nome;

    /**
     * Descriчуo opcional da categoria
     *
     * @var string TEXT
     */
    protected $descricao;

    /**
     * SuperLink para encontrar as categorias certas
     * @var SuperLink INTEGER(10)
     */
    protected $superLink;

    /**
     * Define uma categoria pai para a categoria
     * @var Categoria INTEGER
     */
    protected $categoriaPai;

    protected $filhas = array();

    protected $pags = array();

    public function __construct($id="") {
        parent::__construct($id);
    }


    /**
     * Carrega todas as pсgians a partir de uma categoria
     * @param int|Categoria $categoria
     * @return array
     */
    public function getPaginasLista($limit="") {

        if(empty($this->pags)) {

            $cat = new PaginaCategoria();
            $cat->getDao()->setAtributes("pagina");

            $pags = array();

            $p = new Pagina();
            $p->getDao()->setAtributes("id" , "cleanTitulo" , "titulo" , "subTitulo");


            $whereStr = "categoria=".$this->getId();
            foreach ($this->getCatFilhas() as $catItem) {
                $whereStr .= " OR categoria=".$catItem;
            }

            foreach($cat->getDao()->loadArrayList($whereStr , '' , $limit) as $c) {
                if($c->getPagina() instanceof Pagina) {
                    if($c->getPagina()->getStatus()==Pagina::STATUS_PUBLICADO) {
                        $pags[] = $c->getPagina();
                    }
                }
            }

            if(!empty($order)) {
                DAO::orderBy($pags, $orderPropertyName, $desc);
            }

            $p->getDao()->clearAtributes();
            $this->pags = $pags;
        }



        return $this->pags;

    }

    /**
     * Retorna um array de todos as categorias filha
     *
     * @param string $order
     * @param string|int $limit
     * @return array
     */
    public function getCatFilhas($order = "nome ASC" , $limit="") {
        if(empty ($this->filhas)) {
            $this->filhas = $this->getDao()->loadArrayList("categoriaPai=".$this->id , $order , $limit);
        }
        return $this->filhas;
    }

    /**
     * Encontra a categoria mais alta das categorias
     * @return Categoria
     */
    public function getTopLevelCat() {
        $cat = $this;
        while($cat->getCategoriaPai() instanceof Categoria) {
            $cat= $cat->getCategoriaPai();
        }
        return $cat;
    }

    public function getNumPages() {
        $r = CRUD::query("SELECT COUNT(*) as cont FROM ".Samus::getTablePrefix()."pagina_categoria WHERE categoria=".$this->getId());
        $total = $r[0]['cont'];
        if(empty($total)) {
            $total = 0;
        }
        return $total;
    }

    public function getFilhas() {
        return $this->filhas;
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
     * @return SuperLink
     */
    public function getSuperLink() {
        return $this->superLink;
    }

    /**
     * @param SuperLink $superLink
     */
    public function setSuperLink(SuperLink $superLink) {
        $this->superLink = $superLink;
    }

    /**
     * Obtem a categoria pai
     * @return Categoria
     */
    public function getCategoriaPai() {
        return $this->categoriaPai;
    }

    /**
     * Define uma categoria pai
     * @param Categoria $categoriaPai
     */
    public function setCategoriaPai(Categoria $categoriaPai) {
        $this->categoriaPai = $categoriaPai;
    }


    public function setFilhas($filhas) {
        $this->filhas = $filhas;
    }

    public function getPags() {
        return $this->pags;
    }

    public function setPags($pags) {
        $this->pags = $pags;
    }



}


?>