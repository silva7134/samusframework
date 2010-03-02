<?php
class Swf {

    public static $scriptIncluido = false;

    public static $swfCont = 0;

    private $jsFile = "../scripts/swfobject.js";

    const DEFAULT_PREFIX = "_swfobject";

    public function __construct() {}

    public function script() {
	if(! Swf::$scriptIncluido)
	    echo "<script src='" . $this->getJsFile() . "' type='text/javascript'></script>";
	Swf::$scriptIncluido = true;
    }


    public function showSwf($file, $largura="", $altura="", $wmode = "transparent", $quality = "hight", $scale = "noscale", $titulo = "") {

	self::$swfCont = self::$swfCont + 1;

	$idName = self::DEFAULT_PREFIX . self::$swfCont;

	if(empty($quality)) {
	    $quality = "best";
	}

	$str .= "



    <script type='text/javascript'>
var flashvars = {};
var params = {

    wmode : '$wmode' 

};
var attributes = {};

    swfobject.registerObject('$idName', '9.0.0', '$file', flashvars, params, attributes);
    </script>

      <object id='$idName' classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' data='$file' width='$largura' height='$altura'>
        <param name='wmode' value='transparent' />
        <param name='movie' value='$file' />
        <!--[if !IE]>-->
        <object type='application/x-shockwave-flash' data='$file' width='$largura' height='$altura'>
            
        <!--<![endif]-->
          <p>Baixe o flash</p>
        <!--[if !IE]>-->
        </object>
        <!--<![endif]-->
      </object>




	    ";

	return $str;

    }


    public function exibirSwf($arquivo, $largura="", $altura="", $wmode = "transparent", $quality = "hight", $scale = "noscale", $titulo = "") {

	if(empty($largura) && empty($altura))
	    list($largura, $altura) = getimagesize((string) $arquivo);

	//$subsArquivo = substr($arquivo,0,-4);
	$subsArquivo = str_replace(".swf", "", $arquivo);
	echo "<script type='text/javascript'>
                AC_FL_RunContent('codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','$largura','height','$altura','title','$titulo','src','$subsArquivo','quality','$quality','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','wmode','$wmode','scale','$scale','movie','$subsArquivo' ); //end AC code
                </script>
                <noscript>
                <object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0' width='$largura' height='$altura' title='Swf'>
                  <param name='movie' value='$arquivo' />
                  <param name='quality' value='$quality' />
                  <param name='wmode' value='$wmode' />
                  <param name='SCALE' value='$scale' />
                  <embed src='$arquivo' width='$largura' height='$altura' quality='$quality' pluginspage='http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash' type='application/x-shockwave-flash' wmode='$wmode' scale='$scale'></embed>
                </object></noscript>";
    }

    public function exibirNormal($arquivo, $largura, $altura, $wmode = "transparent", $quality = "hight", $scale = "noscale") {
	$swf = "<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0' width='$largura' height='$altura'>
                                  <param name='movie' value='$arquivo' />
                                  <param name='quality' value='$quality' />
                                  <param name='scale' valie='$scale' />
                                  <param name='wmode' value='$wmode' />
                                  <embed wmode='$wmode' src='$arquivo' quality='$quality' pluginspage='http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash' type='application/x-shockwave-flash' width='$largura' height='$altura'></embed>
                                </object>";
	return $swf;
    }

    public function getJsFile() {
	return $this->jsFile;
    }

    public function setJsFile($jsFile) {
	$this->jsFile = $jsFile;
    }
}
?>
