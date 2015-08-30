# [Samus Framwork ](.md) #

![http://www.samus.com.br/web/media/images/samus_framework_topo_google_code.png](http://www.samus.com.br/web/media/images/samus_framework_topo_google_code.png)

A Samus possui uma plataforma exclusiva  de desenvolvimento, o Samus Framework, não quer dizer que utilizamos uma linguagem de programação diferente de outras empresas, mas que utilizamos uma abordagem muito diferente do comum para o desenvolvimento de aplicações. Seguindo o Modelo MVC o Samus Framework integra-se com o CRUD PHP  (nossa api de persistência) e usa um Template Engine muito famoso, o Smarty, que é uma obra prima do PHP.

Repensamos o Modelo MVC aplicado à projetos web, mas por que? Porque Acreditamos em algumas coisas (que parecem obviais e certas) mas não são aplicadas às ferramentas de desenvolvimento em PHP mais comuns. Entre elas estão

Um projeto web deve ser orientado a objetos, e não à tabelas do banco de dados; as regras de negócio devem estar nas classes modelo, não no banco de dados, a função deste é fazer a persistência dos dados

Código não deve ser misturar,  PHP não pode estar junto com XHTML, XHTML não deve se miturar nem à CSS nem à JavaScript.

PHP não deve ser usado para gerar código HTML, nem deve ser usado para gerar código em JavaScript, essas linguagens são diferentes porque tem funções diferentes.

A camada de dados (DAO) deve ser auto-suficiente, dispensando o conhecimento avançado de banco de dados para o desenvolvedor.

A camada de visão deve ser capaz de acessar tudo que for público em seu controlador

|<a href='http://www.samus.com.br'> <img src='http://samus.com.br/web/media/images/site/samus_logo.png' /></a> | <a href='http://www.php.net'><img src='http://samus.com.br/web/media/images/site/logo-php.png' /></a> | <a href='http://mysql.com/'><img src='http://samus.com.br/web/media/images/site/logo-mysql.png' /></a> | <a href='http://www.smarty.net/'><img src='http://samus.com.br/web/media/images/site/logo-smarty.png' /></a> | <a href='http://www.w3c.org'><img src='http://samus.com.br/web/media/images/site/logo-w3c.png' /></a> | <a href='http://netbeans.org/'><img src='http://samus.com.br/web/media/images/site/logo-netbeans.png' /></a> | <a href='http://www.jquery.com'><img src='http://samus.com.br/web/media/images/site/logo-jquery.png' /></a> |
|:-------------------------------------------------------------------------------------------------------------|:------------------------------------------------------------------------------------------------------|:-------------------------------------------------------------------------------------------------------|:-------------------------------------------------------------------------------------------------------------|:------------------------------------------------------------------------------------------------------|:-------------------------------------------------------------------------------------------------------------|:------------------------------------------------------------------------------------------------------------|

<a href='http://samus.com.br/web/site/solucao-cat=28'>
<img src='http://samus.com.br/web/media/images/site/solucao-open-source.png' />
</a>
_Samus Open Source_

