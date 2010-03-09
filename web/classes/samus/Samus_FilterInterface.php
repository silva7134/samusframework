<?php


interface Samus_FilterInterface {
		
	/**
	 * O filtro é executado sempre que a classe Filtro é executada, todas as 
	 * implementações devem ser feitas no construtor e em filter
	 */
	public function filter();
	
	
}


?>