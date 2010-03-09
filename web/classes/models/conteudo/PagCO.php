<?php
require_once 'samus/Samus_ModelController.php';

class PagCO extends Samus_ModelController {

    public static $_arvore = "";

    /**
     * @return Pag
     */
    public function getObject() {
        return parent::getObject();
    }

    public function getPaginasArvore($superLink , $url="" , $rootCSSClassUl="" , $childCSSClassUL="" , $showSubTitulo=true , $whereCondition='') {

        PagCO::$_arvore = "";
        PagCO::$_arvore .= "<ul class='$rootCSSClassUl'>";

        function PagCO_montarArvore($pagItem , $url , $childCSSClassUL="" , $showSubTitulo=true) {
            $array = array();

            $pf = $pagItem->getCO()->getPaginasFilhas();

            /*@var $pagItem Pag*/
            if(is_array($pf) && !empty($pf)) {

                PagCO::$_arvore .= "<li class='$childCSSClassUL'>";
                PagCO::$_arvore .= "<a href='$url".$pagItem->getCleanTitulo()."' title='$pagItem->titulo - $pagItem->subTitulo' >". $pagItem->titulo."</a>";

                if($showSubTitulo) {
                    PagCO::$_arvore .= " - " . $pagItem->getSubTitulo();
                }

                PagCO::$_arvore .= "</li>";

                foreach($pf as $key => $f) {
                    PagCO::$_arvore .= "<ul>";
                    PagCO_montarArvore($f , $url , $childCSSClassUL);
                    PagCO::$_arvore .= "</ul>";
                }



            } else {
                PagCO::$_arvore .= "<li class='$childCSSClassUL'>";
                PagCO::$_arvore .= "<a href='$url".$pagItem->getCleanTitulo()."' title='$pagItem->titulo - $pagItem->subTitulo' >". $pagItem->titulo."</a> ";

                if($showSubTitulo) {
                    PagCO::$_arvore .= " - " . $pagItem->getSubTitulo();
                }

                PagCO::$_arvore .= "</li>";
            }
        }

        if(!empty($whereCondition)) {
            $whereCondition = ' AND ' . $whereCondition;
        }

        foreach($this->getDao()->loadArrayList("paginaPai IS NULL AND superLink=$superLink  $whereCondition" , "ordem ASC") as $p) {
            /*@var $p Pag*/
            PagCO::$_arvore .= "<ul>";
            PagCO_montarArvore($p , $url , $childCSSClassUL);
            PagCO::$_arvore .= "</ul>";
        }

        $arvore = array();


        PagCO::$_arvore .= "</ul>";
        return PagCO::$_arvore;

    }

    /**
     * Obtem um array de paginas filhas da pagina atual
     * @return array de Pag
     */
    public function getPaginasFilhas() {
        return $this->getDao()->loadArrayList("paginaPai=".$this->getObject()->getId() , 'ordem ASC');
    }

    /**
     * Define se a pagina tem alguma pagina filha
     * @return boolean
     */
    public function temFilhas() {
        $this->getDao()->setAtributes("id");
        $r = $this->getDao()->loadArrayList("paginaPai=".$this->getObject()->getId() , '' , 1);
        $this->getDao()->clearAtributes();
        if(empty($r)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Define se esta página é uma pagina pai
     * @return boolean
     */
    public function isPai() {
        return $this->temFilhas();
    }

    /**
     * Define se a pagina é uma pagina filha
     * @return boolean
     */
    public function isFilha() {
        if($this->getObject()->getPaginaPai()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Carraga e obtem os campos extras
     * @return array
     */
    public function getExtras() {
        if(empty($this->getObject()->extras)) {
            $this->loadExtras();
        }
        return $this->getObject()->extras;
    }

    public function loadExtras() {
        $campoExtra = new CampoExtra();

        $a = array();

        foreach($campoExtra->getDao()->loadArrayList("pagina=".$this->getObject()->getId()) as $p) {
            /*@var $p CampoExtra */
            $a[$p->getCampoExtraGrupo()->getNome()] = $p->getTexto();
        }

        $a = (object) $a;

        $this->getObject()->extras = $a;
    }

    /**
     * Carrega o ultimo item a partir de um superLink
     * @param int|SuperLink $superLink
     * @return Pag
     */
    public function loadLastFromSuperLink($superLink) {
        $a = $this->getObject()->getDao()->loadArrayList("superLink=$superLink", "ordem ASC", 1);
        if($a[0] instanceof Pag) {
            return $a[0];
        } else {
            return new Pag();
        }
    }


    /**
     * Carrega todas as págians a partir de uma categoria
     * @param int|Categoria $categoria
     * @return array
     */
    public function loadFromCategoria($categoria ,$limit="" ,  $orderPropertyName="" , $desc=false) {

        $cat = new PaginaCategoria();
        $cat->getDao()->setAtributes("pagina");

        $pags = array();

        $categoria = new Categoria((int) $categoria);
        $whereStr = "categoria=$categoria";
        foreach ($categoria->getCatFilhas() as $catItem) {
            $whereStr .= " OR categoria=".$catItem;
        }

        foreach($cat->getDao()->loadArrayList($whereStr , '' , $limit) as $c) {
            
            if($c->getPagina() instanceof  Pagina) {
                $pags[$c->getPagina()->getId()] = $c->getPagina();
            }
        }
        

        if(!empty($order)) {
            DAO::orderBy($pags, $orderPropertyName, $desc);
        }

        return $pags;

    }
    
    /**
     * Carrega todas as págians a partir de uma categoria
     * @param int|Categoria $categoria
     * @return array
     */
    public function getPagsFromCategoria($categoria ,$limit="" ,  $orderPropertyName="" , $desc=false) {
       return $this->loadFromCategoria($categoria , $limit , $orderPropertyName , $desc);
    }


    /**
     * Obtem a categoria de mais baixo ID vinculado à peça
     * @return Categoria
     */
    public function getCategoria() {
        if(empty($this->getObject()->_categoria)) {
            $pc = new PaginaCategoria();
            $pc->getDao()->setAtributes("id" , "categoria");
            $pc->getDao()->load("pagina=".$this->getObject()->getId());

            $this->getObject()->_categoria = $pc->getCategoria();


            if($this->getObject()->_categoria instanceof Categoria) {
                CRUD::$useObjCache = false;

                $cat = new Categoria($this->getObject()->_categoria->getId());

                if($cat->getCategoriaPai() instanceof Categoria) {
                    $this->getObject()->_categoria = $cat->getCategoriaPai();
                }
                CRUD::$useObjCache = true;
            }
        }



        return $this->getObject()->_categoria;

    }




    /**
     * Carrega a foto a partir do cleanTitulo, pode ser numerico ou o cleanTitulo mesmo
     * @param string $cleanTitulo
     * @return void
     */
    public function loadFromCleanTit($cleanTitulo) {
        if (is_numeric ( $cleanTitulo )) {
            $this->getObject()->getDao ()->load ( $cleanTitulo );
        } else {
            $this->getObject()->getDao ()->load ( "cleanTitulo='$cleanTitulo'" );
        }
    }

    /**
     * Retorna um array das fotos da pagina
     * @return array
     */
    public function getFotos() {
        if(empty($this->getObject()->fotos)) {
            $pf = new PaginaFoto ( );
            $this->getObject()->fotos = $pf->getDao ()->loadLightArray ("pagina=" . $this->getObject()->getId () , 'id ASC');
        }

        return $this->getObject()->fotos;
    }

    /**
     * Retorna a foto principal da pagina
     * @return PaginaFoto
     */
    public function getImg() {
        if (empty ( $this->getObject()->fotos )) {
            $this->getObject()->getFotos ();
        }
        foreach ( $this->getObject()->fotos as $f ) {
            /*@var $f PaginaFoto */
            if ($f->isPrincipal ()) {
                return $f;
                break;
            }
        }
    }



    /**
     * Obtem todos os arquivos da pagina
     * @return array
     */
    public function getArquivos() {

        if(empty($this->getObject()->arquivosArray)) {
            $pa = new PaginaArquivo();
            $this->getObject()->arquivosArray = $pa->getDao()->loadArrayList("pagina=".$this->getObject()->id, "id DESC");
            ;
        }

        return $this->getObject()->arquivosArray;

    }

    /**
     * @return string
     */
    public function getTagsString() {

        $pt = new PaginaConteudoTag ( );
        $str = "";
        foreach ( $pt->getDao ()->loadArrayList ( "pagina=" . $this->getObject()->id ) as $t ) {
            /*@var $t PaginaConteudoTag */
            $str .= $t->getConteudoTag ()->getNome () . ',';

        }
        return substr ( $str, 0, - 1 );
    }

    /**
     * @return array
     */
    public function getTags() {

        $pt = new PaginaConteudoTag ( );
        $tags = array();
        foreach ( $pt->getDao ()->loadArrayList ( "pagina=" . $this->getObject()->id ) as $t ) {
            /*@var $t PaginaConteudoTag */
            $tags[] = $t->getConteudoTag();

        }
        return $tags;
    }


    /**
     * Obtem todas as enquetes da página
     * @return array
     */
    public function getEnquetes($limit="") {
        require_once 'models/conteudo/ConteudoEnquete.php';
        require_once 'models/conteudo/PaginaConteudoEnquete.php';


        $pe = new PaginaConteudoEnquete();
        $pe_array = $pe->getDao()->loadArrayList("pagina=" . $this->getObject()->getId(),"id DESC",$limit);



        $enquetes = array();

        foreach($pe_array as $p) {
            $enquetes[] = $p->getConteudoEnquete();
        }


        return $enquetes;
    }


    /**
     * Obtem os comentários de uma página conforme  o status escolhido
     * @param string $order
     * @param string|int $limit
     * @param int $status
     * @return array
     */
    public function obterComentarios($order="id DESC" , $limit="" , $status=1) {
        $comentario = new ConteudoComentario();
        return $comentario->getDao()->loadArrayList("pagina=".$this->getObject()->getId()." AND status=$status");
    }


}

?>