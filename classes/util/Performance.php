<?php

/**
 * Classes para controle e análise de desempenho de códigos
 * 
 * @author Vinicius Fiorio - Samusdev@gmail.com
 * @package util
 * @filesource Performance.php
 *
 */
class Performance {
	
	
	/**
	 * Registra o tempo de execução de um código, este método deve ser chamado 
	 * antes do script e depois e exibirá o tempo de execução do código
	 *
	 */
	public static function getTime(){
		static $tempo;
		if( $tempo == NULL ){
			$tempo = microtime(true);
		}
		else{
			echo '<hr>Tempo (seg.): '.(microtime(true)-$tempo).'<hr> <br>';
		}
	}
	
	public static function getMemoryUsage() {
	    echo '<hr>'.memory_get_usage().'<hr><br/>';
	}
	
}


?>