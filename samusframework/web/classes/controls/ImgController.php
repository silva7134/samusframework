<?php
require_once "foto/Foto.php";

/**
 * @author Vinicius Fiorio - samusdev@gmail.com
 */
class ImgController extends Samus_Controller {

    public function index() {
        error_reporting(E_ALL);
        $foto = new Foto();
        $largura = Samus_Keeper::getUrl(2);
        $altura = Samus_Keeper::getUrl(3);


        $foto->setTamanho($largura, $altura);


        $foto->setQualidade(87);



        $foto->exibirImagem(Samus_Keeper::getUrl(1));


        $this->setTemplateFile("vazio");
    }


}
?>
