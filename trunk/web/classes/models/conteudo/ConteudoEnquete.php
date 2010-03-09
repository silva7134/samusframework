<?php
require_once 'util/CleanString.php';
require_once ('samus/Samus_Model.php');
require_once 'models/conteudo/ConteudoEnqueteResposta.php';

class ConteudoEnquete extends Samus_Model {

/**
 * Pergunta da enquete
 *
 * @var string VARCHAR(200)
 */
    protected $pergunta;

    /**
     * Data de criação da enquete
     *
     * @var string DATETIME
     */
    protected $data;

    protected $enqueteResposta = array();

    protected $conteudoEnqueteResposta = array();




    public function getEnqueteResposta() {
        return $this->enqueteResposta;
    }
    /**
     * @param $conteudoEnqueteResposta the $conteudoEnqueteResposta to set
     */
    function setConteudoEnqueteResposta($conteudoEnqueteResposta) {
        $this->conteudoEnqueteResposta = $conteudoEnqueteResposta;
    }

    /**
     * @return the $conteudoEnqueteResposta
     */
    function getConteudoEnqueteResposta() {
        return $this->conteudoEnqueteResposta;
    }


    public function setEnqueteResposta($enqueteResposta) {
        $this->enqueteResposta = $enqueteResposta;
    }



    public function exibirResultado($exibirGrafico=false) {
        $respostas = $this->getRespostas();



        $totalVotos = $this->getTotalVotos($respostas);
        $str = $this->getPergunta() . '<br>';
        $str = "";

        if($exibirGrafico) {
            $str .= $this->graficoResultado('p3', '32b343', 520, 200, 'right').'<br /><br />';
        }


        $graficoValores = "";
        $graficoNomes = "";

        foreach($respostas as $key => $r) {
        //$r = new ConteudoEnqueteResposta();

            $respost = $r->resposta;

            if(!empty($respost)) {

                $str .= "<strong>$respost</strong> <br />";

                if($totalVotos == 0) {
                    $porcentagem = 0;
                } else {
                    $porcentagem = $r->getVotos() / $totalVotos;
                }



                $porcentagem = number_format($porcentagem * 100,2);

                $graficoValores .= $porcentagem.',';
                $graficoNomes .=  CleanString::removeAcento($respost).'|';


                for($i=0 ; $i < round($porcentagem) ; $i++) {
                    $str .= "|";
                }
                $str .= " " . $porcentagem . "%";
                $str .= "<br>";
            }
        }

        $graficoValores = substr($graficoValores , 0 , -1);
        $graficoNomes = substr($graficoNomes , 0 , -1);



        return $str;

    }

    public function graficoResultado($tipo="p3" , $cor="32b343" , $lagura=450 , $altura=200 , $cssClass="") {
        $respostas = $this->getRespostas();

        $totalVotos = $this->getTotalVotos($respostas);
        $str = "";


        $graficoValores = "";
        $graficoNomes = "";

        foreach($respostas as $key => $r) {
        //$r = new ConteudoEnqueteResposta();

            $respost = $r->resposta;

            if(!empty($respost)) {

                if($totalVotos == 0) {
                    $porcentagem = 0;
                } else {
                    $porcentagem = $r->getVotos() / $totalVotos;
                }

                $porcentagem = number_format($porcentagem * 100,2);

                $graficoValores .= $porcentagem.',';
                $graficoNomes .=  CleanString::removeAcento($respost).'|';

            }
        }

        $graficoValores = substr($graficoValores , 0 , -1);
        $graficoNomes = substr($graficoNomes , 0 , -1);

        $str .= "
<img src='
http://chart.apis.google.com/chart?
chs=".$lagura."x".$altura."
&chd=t:$graficoValores
&chco=$cor
&cht=$tipo
&chl=$graficoNomes
' alt='Grafico' width='$lagura' height='$altura' class='$cssClass' />";

        return $str;
    }

    /**
     * @return string
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getPergunta() {
        return $this->pergunta;
    }

    /**
     * @param string $data
     */
    public function setData($data) {
        $this->data = $data;
    }

    /**
     * @param string $pergunta
     */
    public function setPergunta($pergunta) {
        $this->pergunta = $pergunta;
    }

    /**
     * Obtem as respostas de uma enquete
     * @return array
     */
    public function getRespostas() {
        $resposta = new ConteudoEnqueteResposta();
        return $resposta->getDao()->loadArrayList("conteudoEnquete=$this->id", "id ASC");
    }


    public function getTotalVotos($respostas) {
        $votos = 0;

        foreach($respostas as $r) {
            $votos += $r->getVotos();
        }
        return $votos;
    }

}

?>