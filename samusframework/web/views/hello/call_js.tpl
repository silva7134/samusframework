<?xml version="1.0" encoding="ISO-8859-1"?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <script type="text/javascript" src="${ $samus.const.WEB_URL }scripts/jquery/jquery-1.4.2.min.js">
        </script>
        <script type="text/javascript" src="${ $samus.const.WEB_URL }scripts/samus/sf.ajax.js">
        </script>
        <style type="text/css">
            
            body {
                font-family: Tahoma;
                font-size: 12px;
            }
        </style>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <title>Call Js</title>
    </head>
    <body>
        <div>
            <div id="result">
            </div>
            <h1>Call Js</h1>
            <fieldset style="padding: 20px; background-color: #f3f3f3">
                <legend>
                    Eu sou um metodo legal
                </legend>
                <a href="#funcaoAA=MeuParametro" id="elemento">Executar</a>
                <hr/><a href="funcao=Parametro+Diferente+Para+Teste,result" id="elemento2">Executar Parametro2</a>
                <hr/><a href="::boaNoiteWesley=asdasdasd" id="ele">Executar</a>
                <br/>
                <br/>
            </fieldset>
        </div>
    </body>
</html>
