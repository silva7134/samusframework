<?php
/**
 * Interface para as classes que implementam a classe CRUD indicando que aquela
 * classe é persistente e os métodos comuns ao à classe persistente
 *
 * @author Vinicius Fiorio Custodio - Samusdev@gmail.com
 * @version v 1.0.0 16/07/2008
 * @copyright GPL - General Public License
 * @license http://www.gnu.org
 * @link http://www.Samus.com.br
 * @category CRUD
 */
interface Persistent {

	/**
	 * @return string nome da tabela da entidade
	 */
	public static function getDbTable();


	/**
	 * Carrega o objeto a partir do seu id ou de uma condição, o funcionamento é
	 * semelhanta ao WHERE de uma consulta MySql, caso whereCondition seja um
	 * inteiro o objeto será carregado pelo ID da linha da tabela que representa
	 * a entidade, se a string for menor do que 4 ela será automaticamente
	 * convertido para int <br />
	 * <br />
	 * Ex.: <br />
	 * $produto = new Produto(); <br />
	 * $produto->load($id); <br />
	 * var_dump($produto); <br />
	 * <br />
	 * <br />
	 * Ex. 2: <br />
	 * $usuario = new Usuario(); <br />
	 * $usuario->load("email=$email AND senha=$senha"); <br />
	 * var_dump($usuario);
	 * @param int|string $whereCondition
	 */
	public function load($whereCondition);

	/**
	 * @param string $whereCondition
	 * @param string $order
	 * @param int|string $limit
	 */
	public function loadArrayList($whereCondition = "", $order = "", $limit = "");

	/**
	 * @param string $whereCondition
	 */
	public function loadLast($whereCondition = "");

	/**
	 * Realiza o INSERT e UPDATE de qualquer objeto
	 */
	public function save();

	/**
	 * @param mixed $object
	 */
	public function delete();

	public function __tostring();
}
?>
