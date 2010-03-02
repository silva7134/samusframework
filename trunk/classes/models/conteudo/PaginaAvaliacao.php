<?php

require_once ('samus/Samus_Model.php');

class PaginaAvaliacao extends Samus_Model {

/**
 * Página avaliada
 *
 * @var Pagina INTEGER
 */
    protected $pagina;

    /**
     * Contador com o numero total de votos
     *
     * @var int INTEGER
     */
    protected $totalVotos;

    /**
     * Nota máxima que um usuário pode dar
     *
     * @var float FLOAT
     */
    protected $maxNota;

    /**
     * Nota obtida pela votação
     *
     * @var float FLOAT
     */
    protected $nota;

    const DEFAULT_NOTA_MAXIMA = 5;

    /**
     * Obtem a nota média
     * @return float
     */
    public function getNotaMedia() {
        $total = $this->getNotaTotalMaxima();
        if($this->getTotalVotos() != 0) {
            $notaMedia = $this->getNota() / $this->getTotalVotos();
            return round($notaMedia);
        } else {
            return 0;
        }
    }

    /**
     * Obtem a nota máxima que poderia ser obtida
     *
     * @return float
     */
    public function getNotaTotalMaxima() {
        return $this->getMaxNota() * $this->getTotalVotos();
    }

    /**
     * @return float
     */
    public function getMaxNota() {
        return $this->maxNota;
    }

    /**
     * @return float
     */
    public function getNota() {
        return $this->nota;
    }

    /**
     * @return Pagina
     */
    public function getPagina() {
        return $this->pagina;
    }

    /**
     * @return int
     */
    public function getTotalVotos() {
        return $this->totalVotos;
    }

    /**
     * @param float $maxNota
     */
    public function setMaxNota($maxNota) {
        $this->maxNota = $maxNota;
    }

    /**
     * @param float $nota
     */
    public function setNota($nota) {
        $this->nota = $nota;
    }

    /**
     * @param Pagina $pagina
     */
    public function setPagina(Pagina $pagina) {
        $this->pagina = $pagina;
    }

    /**
     * @param int $totalVotos
     */
    public function setTotalVotos($totalVotos) {
        $this->totalVotos = $totalVotos;
    }



}

?>