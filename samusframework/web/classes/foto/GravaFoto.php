<?php
require_once 'Img.php';
require_once "util/Util.php";
/**
 * Faz operações para armazenar e redimensionar imagens
 *
 *
 * @author Vinicius Fiorio Custodio - Samusdev@gmail.com
 * @version v 1.0.1 23/07/2008
 * @copyright GPL - General Public License
 * @license http://www.gnu.org
 * @link http://www.Samus.com.br
 * @category Gerenciamento de Imagens
 */
class GravaFoto extends Img {

/**
 * Nome do campo do formulário para gravar a foto
 *
 * @var string
 */
    private $formCampo = "foto";

    /**
     * Diretorio de gravação das fotos
     *
     * @var string
     */
    private $diretorio = '../fotos/';


    /**
     * Se o nome da foto será alterado para um nome unico
     *
     * @var boolean
     */
    private $mudarNome = true;

    /**
     * Se desejar exibir as mensagens de gravação
     *
     * @var boolean
     */
    private $mensagens = false;


    /**
     * Nome original da foto gravada
     *
     * @var string
     */
    private $nomeOriginal;

    public static $contador = 1; //conta o numero de fotos postadas


    /**
     * Sufixo para a renomeacao dos arquivos
     * @var string
     */
    public $newNameSufix;
    /**
     *
     * @param string $diretorio
     * @param sitring $formCampo
     * @param string$mudarNome
     * @param string $mensagens
     */
    public function GravaFoto($diretorio = '../fotos/', $formCampo = 'foto',
        $mudarNome = true, $mensagens = false) {
        $this->diretorio = $diretorio;
        $this->formCampo = $formCampo;
        $this->mudarNome = $mudarNome;
        $this->mensagens = $mensagens;
    }

    /**
     * Grava uma imagem no diretorio especificado  com as dimenções especificados na superclasse
     * @return string nome do arquivo gravado
     */
    public function gravar() {
        self::$contador ++;
        $util = new Util();
        $this->setTipo($_FILES[$this->formCampo]['name']);
        if($_FILES[$this->formCampo]['error'] > 0) {
            $mensagem .= 'Erro: ';
            switch ($_FILES[$this->formCampo]['error']) {
                case 1:
                    $util->erroVolta(
                        'O arquivo é maior do que o limite definido pelo servidor web<br>');
                    break;
                case 2:
                    $util->erroVolta(
                        'O arquivo é maior do que o limite definido pelo sistema<br>');
                    break;
                case 3:
                    $util->erroVolta(
                        'O upload do arquivo foi feito parcialmente');
                    break;
                case 4:
                    $util->erroVolta(
                        'Não foi feito o upload do arquivo');
                    break;
            }
            exit();
        }

        $this->setNomeOriginal($_FILES[$this->formCampo]['name']);

        if($this->mudarNome) {
            $data = date(ymdHis);
            $data .= self::$contador;
            $novoNome = $this->getNewNameSufix() .'_'. $data . $this->tipo;
            $nomeFinal = $novoNome;
        } else {
            $novoNome = $this->getNomeOriginal();
        }

        if(move_uploaded_file(
        $_FILES[$this->formCampo]['tmp_name'],
        $this->diretorio . $novoNome)) {
            $mensagem .= "O arquivo é válido e foi carregado com sucesso<br>";
        } else {
            $util->erroVolta(
                "ERRO ! Possivel ataque de upload! Verifique se o diretorio especificado existe e se tem permissão de escrita");
        }

        return $this->gravarRedimensionar($novoNome);
    }

    public function gravarRedimensionar($arquivoNome) {
        $filename =  $this->diretorio.$arquivoNome;
        $newwidth = $this->largura;
        $newheight = $this->altura;
        // calcula os novos tamanhos
        list ($width, $height) = getimagesize($filename);
        if($newwidth && ($width < $height)) {
            $newwidth = ($newheight / $height) * $width;
        } else {
            $newheight = ($newwidth / $width) * $height;
        }
        // Carrega a foto
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        if($this->tipo == ".jpg") { //caso seja uma imagem .jpg
            $source = imagecreatefromjpeg($filename);
            imagecopyresampled(
                $thumb,
                $source,
                0,
                0,
                0,
                0,
                $newwidth,
                $newheight,
                $width,
                $height);
            if(imagejpeg($thumb, $filename, $this->qualidade)) {
                $mensagem .= 'Imagem redimensionada com sucesso <br />';
            } else {
                $util->erroVolta(
                    "ERRO ! O redimencionamento da imagem não pode ser executado");
            }
        } elseif($this->tipo == ".gif") {
            $source = imagecreatefromgif($filename);
            imagecopyresampled(
                $thumb,
                $source,
                0,
                0,
                0,
                0,
                $newwidth,
                $newheight,
                $width,
                $height);
            if(imagegif($thumb, $filename)) {
                $mensagem .= 'Imagem redimensionada com sucesso <br />';
            } else {
                $util->erroVolta(
                    "ERRO ! O redimencionamento da imagem não pode ser executado");
            }
        } elseif($this->tipo == ".png") {
            $source = imagecreatefrompng($filename);
            imagecopyresampled(
                $thumb,
                $source,
                0,
                0,
                0,
                0,
                $newwidth,
                $newheight,
                $width,
                $height);
            if(imagepng($thumb, $filename)) {
                $mensagem .= 'Imagem redimensionada com sucesso <br />';
            } else {
                $util->erroVolta(
                    "ERRO ! O redimencionamento da imagem não pode ser executado");
            }
        }
        if($this->mensagens) {
            echo $mensagem;
        }

        return $arquivoNome;
    }

    /**
     * Testa se o arquivo exsite e realiza a gravação
     */
    public function testaGrava() {
        if(! empty($_FILES[$this->formCampo]['name']))
            $this->gravar();
    }

    /**
     * Seta o nome do campo que tem o arquivo
     * @param string $formCampo
     */
    public function setFormCampo($formCampo = 'foto') {
        $this->formCampo = $formCampo;
    }

    /**
     * Seta o diretorio onde as imagens serão salvas
     * @param string $caminhoDiretorio
     */
    public function setDiretorio($caminhoDiretorio) {
        $this->diretorio = $caminhoDiretorio;
    }


    /**
     * @return string
     */
    public function getNomeOriginal() {
        return $this->nomeOriginal;
    }

    /**
     * @param string $nomeOriginal
     */
    public function setNomeOriginal($nomeOriginal) {
        $this->nomeOriginal = $nomeOriginal;
    }

    public function getNewNameSufix() {
        return $this->newNameSufix;
    }

    public function setNewNameSufix($newNameSufix) {
        $this->newNameSufix = $newNameSufix;
    }



}
?>