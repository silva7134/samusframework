<?php
require_once 'email/phpMailer/class.phpmailer.php';
/**
 * Classe de envio de emails da poltex, em seu contrutor são configuradas as propriedades
 * padrão de envio das mensagens do site da poltex
 *
 * @author samus
 */
class EmailEnvio extends PHPMailer {

/**
 *
 * @var PHPMailer
 */
    public $phpMailer;



    public function __construct() {
        $this->IsSMTP();
        $this->SMTPAuth = true;
        $this->SMTPSecure = "ssl";
        $this->Port = "465";
        $this->IsHTML(true);
        $this->Host = "smtp.gmail.com";
        $this->Username = "samus@samus.com.br";
        $this->Password = "2santiagosamus";

        $this->From = "contato@samus.com.br";
        $this->FromName = "Samus";
        $this->Subject = "Samus";

    }


    public function enviar($mensagem) {
        $msg = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
<style type="text/css">
      <!--
      body {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 11px;
      }
      -->
    </style>
    <title>Artigo Customizado</title>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
  </head>
  <body>
  <div>
';
        $msg .= $mensagem;
        $msg .= "</div></body></html>";

        $this->Body = $msg;

        return $this->Send();
    }


}
?>
