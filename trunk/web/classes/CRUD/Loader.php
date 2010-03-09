<?php
/**
 * Loader faz as operações da classe DAO sem que seja necessário uma intancia
 * de um objeto estendendo a classe DAO
 *
 * @author Vinicius Fiorio Custodio - Samusdev@gmail.com
 * @version v 1.0.1
 * @copyright GPL - General Public License
 * @license http://www.gnu.org
 * @link http://www.Samus.com.br
 * @category CRUD
 * @package CRUD
 */
class Loader {

    /**
     * Obtem um objeto da classe especificada carregada com os parâmetros fornecidos
     * em $whereCondition
     * @param string $className nome da classe
     * @param string|int $whereCondition condição de carregamento
     * @param boolean $loadArrayAtributes se os atributos do tipo array devem ser carregados
     * @return object
     */
    public static function load($classOrObject , $whereCondition , $loadArrayAtributes = false) {
        if(strlen($whereCondition) < 4)
        $whereCondition = (int) $whereCondition;

        $crud = new CRUD($classOrObject);
        $obj = self::getInstance($classOrObject);


        $crud->mountObject($whereCondition, "" , $obj);
        if($loadArrayAtributes) {
            $crud->loadArrayAttributes($obj);
        }

        return $obj;
    }

    /**
     * Alias to load
     * @see function load
     */
    public static function find($classOrObject , $whereCondition , $loadArrayAtributes = false) {
        return self::load($classOrObject, $whereCondition, $loadArrayAtributes);
    }


    /**
     * Lista os objetos da classe colocando em um vetor os objetos <br />
     * Ex.: <br />
     * $produto = new Produto(); <br />
     * $produtos_array = $produto->loadArrayList("categoria = 1"); <br />
     * var_dump($produtos_array); <br />
     * <br />
     *
     * @param string $whereCondition
     * @param string $order
     * @param string|int $limit
     * @return Matrix
     */
    public static function loadArrayList($classOrObject , $whereCondition = "", $order = "", $limit = "") {
        $crud = new CRUD($classOrObject);
        $obj = self::getInstance($classOrObject);

        return $crud->loadLightArray(
            $whereCondition,
            $order,
            $limit,
            false,
            TRUE);
    }

    /**
     * Analise a classe e as associaçoes e retorna um array associativo com os
     * resultados, é a consulta mais rápida dos dados mas não retorna Objetos,
     * ótimo para listagens que é essencial o desempenho
     *
     * @param string $whereCondition
     * @param string $order
     * @param string|int $limit
     * @return array array associativo
     */
    public static function loadAssociativeArrayList($classOrObject , $whereCondition = "", $order = "", $limit = "") {

        $crud = new CRUD($classOrObject);
        $obj = self::getInstance($classOrObject);

        return $crud->loadLightArray($whereCondition, $order, $limit, true);
    }


	/**
	 * Carrega um array de objetos leves, objetos leves são objetos que não tem
	 * o seus atributos do tipo OBjeto carregados, eles requerem novas querys e
	 * comprometem o desempenho de carregamento de longas listas
	 *
	 * @param string $whereCondition
	 * @param string $order
	 * @param string|int $limit
	 * @return Matrix
	 */
	public static function loadLightArray($classOrObject , $whereCondition = "", $order = "", $limit = "") {
        $crud = new CRUD($classOrObject);
        $obj = self::getInstance($classOrObject);
		return $crud->loadLightArray($whereCondition, $order, $limit);
	}


	/**
	 * Ordena um array de Objetos a partir de uma propriedade qualquer, é possível
	 * ordenar por uma propriedade de uma propriedade que seja também um objeto
	 *
	 * Ex.:
	 * DAO::orderBy($array , 'name');
	 * DAO::orderBy($array , 'property->property->property->name');
	 *
	 * @param array $objectArray
	 * @param string $propertyName
	 * @param boolean $desc se será em ordem inversa
	 */
	public static function orderBy(&$objectArray , $propertyName , $desc=false) {
		$isObj = false;
		if($objectArray instanceof Matrix) {
			$objectArray = $objectArray->getArrayCopy();
			$isObj = true;
		}

		function _daoObjSort(&$objArray,$indexFunction,$propertyName,$desc,$sort_flags=0) {
		    $indices = array();
		    foreach($objArray as $obj) {
		        $indeces[] = $indexFunction($obj,$propertyName);
		    }

		    $c = array_multisort($indeces,$objArray,$sort_flags);

		    if($desc) {
		    	$objArray = array_reverse($objArray);
		    }
		}

		function _daoGetIndex($obj , $propertyName) {
			$val = null;
			$strEval = '$val = $obj->'.$propertyName.'; ';
			eval($strEval);
			return $val;
		}


      _daoObjSort($objectArray , '_daoGetIndex' , $propertyName , $desc);

      	if($isObj) {
      		return new Matrix($objectArray);
      	} else {
      		return $objectArray;
      	}
	}

	/**
	 * Carreta os atributos do tipo array do objeto, são as ligações 0..*
	 *
	 * classe tem com suas associa??es
	 */
	public static function loadObjectArrayAttributes($object) {
        $crud = new CRUD($object);
		$crud->loadArrayAttributes($object);
	}


	/**
	 * Carrega uma lista de objetos a partir de uma sql qualquer, o caracter '?'
	 * será substituido pelo nome da tabela corrente
	 *
	 * @param string $sql
	 * @param $loadObjectAtributes se os atrivutos do tipo objeto serão carregados
	 * @return array de objetos
	 */
	public static function loadArrayObjectsFromSql($classOrObject , $sql, $loadObjectAtributes = true) {
        $crud = new CRUD($classOrObject);
        $obj = self::getInstance($classOrObject);
		return $crud->loadArrayFromSql($sql, $loadObjectAtributes);
	}
	/**
     *
	 * Carrega o ultimo objeto registrado no banco da entidade <br />
	 * @param string $whereCondition
	 */
	public static function loadLast($classOrObject , $whereCondition = "") {
		$crud = new CRUD($classOrObject);
        $obj = self::getInstance($classOrObject);
		$crud->loadLastObject($whereCondition, $obj);
	}


	/**
	 * Carrega o primeiro objeto registrado na tabela da entidade que atendem
	 * a condição especificada
	 * <br />
	 * Ex.: <Br />
	 * $produto = new Produto(); <br />
	 * $produto->loadFirst();
	 * @param string $whereCondition
	 */
	public static function loadFirst($classOrObject, $whereCondition = "") {
		$crud = new CRUD($classOrObject);
        $obj = self::getInstance($classOrObject);
		$crud->loadLastObject($whereCondition, $obj, true);
	}


	/**
	 * Salva o objeto no banco. Caso o ID do objeto ja exita no banco o
	 * mesmo será atualizado (UPDATE) senão ele será inserido (INSERT) <br />
	 * @return int|null id do objeto inserido, caso insert
	 */
	public static function save($classOrObject) {
		$crud = new CRUD($classOrObject);
        $obj = self::getInstance($classOrObject);
		return $crud->save($obj);
	}

	/**
	 * Deleta o objeto especificado <br />
	 * Ex.: <br />
	 * $produto = new Produto(); <br />
	 * $produto->setId(1); <br />
	 * $produto->delete(); <br />
	 *
	 * @param mixed $object
	 * @param string $whereCondition
	 * @return boolean
	 */
	public static function delete($classOrObject , $whereCondition = "") {
		$crud = new CRUD($classOrObject);
        $obj = self::getInstance($classOrObject);
		return $crud->delete($obj, $whereCondition);
	}


	/**
	 * Lista todos os elementos a partir dos termos da busca, ele varre a tabela
	 * do banco procurando em qualquer coluna da tabela pelo elemento especificado
	 * por 'search'. <br />
	 * <br />
	 * Ex.: <br />
	 * $produto = new Produto(); <br />
	 * $martelos = $produto->search("martelo"); <br />
	 * var_dump($martelos); <br />
	 * <br />
	 *
	 *
	 * @param string $search
	 * @param string$whereCondtion
	 * @param string $order
	 * @param string|int $limit
	 * @param boolean $exactKeyword
	 * @param $light se é feita uma consulta leve
	 * @return mixed[]
	 */
	public static function search($classOrObject , $search, $whereCondtion = '', $order = '', $limit = '',
		$exactKeyword = false, $light = false) {

        $crud = new CRUD($classOrObject);
        $obj = self::getInstance($classOrObject);


        $exact = "";
		if(! $exactKeyword)
			$exact = "%";

		$crud = $this->myCRUD();

		$atrArray = $crud->getAtributes();
		$query = "(";
		$ai = new ArrayIterator($atrArray);
		while ($ai->valid()) {
			$query .= " " . $crud->getTableName() . "." . $ai->current() . " LIKE '$exact$search$exact'" . " OR ";
			$ai->next();
		}
		$query = substr($query, 0, - 3);
		$query .= ")";
		if(! empty($whereCondtion)) {
			$whereCondtion = trim($whereCondtion);
			if(substr($whereCondtion, 0, 3) != "AND" && substr(
				$whereCondtion,
				0,
				2) != "OR") {
				$whereCondtion = "AND " . $whereCondtion;
			}
			$query = $query . $whereCondtion;
		}


		if($light)
			return $crud->loadLightArray($query, $order, $limit);
		else {
			return $this->loadArrayList($query , $order , $limit);
		}
	}

	/**
	 * Retorna um array associativo do objeto: <br />
	 * $array["propriedade"] = "valor";
	 * @return mixed[]
	 */
	public static function associativeArray($classOrObject) {

        $crud = new CRUD($classOrObject);
        $obj = self::getInstance($classOrObject);

		$crud = $this->myCRUD();
		$exceptions = array(
							"atributes" ,
							'columnIdentifier' ,
							'dbTable' ,
							'dbColumns');

		return $crud->associativeArrayFromEncapsuledObject($obj);
	}



    /**
     * Retorna um node XML do objeto, as tags HTML dos conteudos dos obetos são
     * codigicados por htmlentities()
     *
     * @param string $whereCondition condição de carregamento
     * @param string $order ordem dos registros
     * @param string $limit limite de registros
     * @param boolean $lightMode se serão carregados os objetos dentro dos objetos
     * @param boolean $addXmlRootTags
     * @return string node xml dos registros
     */
    public static function tSamus_Controllerml($classOrObject , $whereCondition = "", $order = "", $limit = "",
        $lightMode = false, $addXmlRootTags = false) {

        $crud = new CRUD($classOrObject);
        $obj = self::getInstance($classNameOrObject);


        $crud->loadLightArray(
            $whereCondition,
            $order,
            $limit,
            false,
            !$lightMode,
            true);

        $str = CRUD::$xmlStr;

        if($addXmlRootTags) {
            $str = '<?xml version="1.0" encoding="ISO-8859-1"?>
<root>' . $str . "</root>";
        }

        CRUD::$xmlStr = "";
        return $str;
    }



    /**
     * Carrega as proprieidades de um objeto a partir de um array associativo,
     * apenas as propriedades especificadas serão carregadas.: <br />
     * <br />
     * Ex.:
     * $array = array( "nome" => "Vnicius Fiorio" , "email" => "Samusdev@gmail.com");
     * $pessoa = new Pessoa();
     * $pessoa->loadObjectFromAssociativeArray($array);
     *
     * @param array $associativeArray
     */
    public static function loadObjectFromAssociativeArray($classOrObject , array $associativeArray) {
        $crud = new CRUD($classOrObject);
        $obj = self::getInstance($classOrObject);
        $crud->mountAssociativeObject($associativeArray, $obj);
    }


    /**
     * Obtem uma intancia de um objeto, se for passado uma string ele retorna uma
     * intancia da classe especificada, se ja for um objeto ele mantem os seu estado
     * @param string|mixed $classNameOrObject
     * @return object
     */
    private static function getInstance($classOrObject) {
        if(is_string($classOrObject)) {
            $ref = new ReflectionClass($classOrObject);
            $obj = $ref->newInstance();
        } else {
            $obj = $classNameOrObject;
        }
        return $obj;
    }


}
?>
