# Introdução #
## http://www.samus.com.br ##

<p>
A Samus possui uma plataforma exclusiva de desenvolvimento, o Samus Framework, não quer dizer que utilizamos uma linguagem de programação diferente de outras empresas, mas que utilizamos uma abordagem muito diferente do comum para o desenvolvimento de aplicações. Seguindo o Modelo MVC o Samus Framework integra-se com o CRUD PHP (nossa api de persistência) e usa um Template Engine muito famoso, o Smarty, que é uma obra prima do PHP.<br>
</p>

<p>
Repensamos o Modelo MVC aplicado à projetos web, mas por que? Porque Acreditamos em algumas coisas (que parecem obviais e certas) mas não são aplicadas às ferramentas de desenvolvimento em PHP mais comuns. Entre elas estão<br>
</p>

<p>
Um projeto web deve ser orientado a objetos, e não à tabelas do banco de dados; as regras de negócio devem estar nas classes modelo, não no banco de dados, a função deste é fazer a persistência dos dados<br>
</p>

<p>
Código não deve ser misturar, PHP não pode estar junto com XHTML, XHTML não deve se miturar nem à CSS nem à JavaScript.<br>
PHP não deve ser usado para gerar código HTML, nem deve ser usado para gerar código em JavaScript, essas linguagens são diferentes porque tem funções diferentes.<br>
A camada de dados (DAO) deve ser auto-suficiente, dispensando o conhecimento avançado de banco de dados para o desenvolvedor.<br>
A camada de visão deve ser capaz de acessar tudo que for público em seu controlador<br>
</p>

<p>
O nosso Framework já esta em uso em dezenas de projetos, no entanto estamos carentes de documentação, esta documentação esta sendo desenvolvida gradualmente conforme caminhamos para versão 1.0 do Framework, os códigos fonte e mais detalhes sobre o desenvolvimento podem ser encotrados no nosso projeto do google:<br>
</p>
http://code.google.com/p/samusframework