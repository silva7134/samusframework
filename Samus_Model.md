# Samus Model #

> A Classe Samus\_Model é responsável por analizar a classe e ligar ela com a camada de
> persistência de dados, ela é capaz de criar as tabelas que representam as
> modelos ("modelo" ou "Samus\_Model" representa uma classe de Modelo do Curral. Todas as
> classes de modelo devem extender a Samus\_Model "class Modelo extends Samus\_Model()".
> <br />\n
> <br />\n
> Os PHPDoc dos atributos são parte do código, a sintaxe é a mesma do PHPDoc
> normal utilizado, a diferenã é que depois da declaração do tipo da variável
> deve ser especificado o tipo de dado da coluna na tabela (simples não) e o
> nome do atributo será o nome da coluna. O nome da tabela criada é o nome do
> atributo 'name' do PHPDoc, espaços e caracteres especiais são removidos.
> <br />\n
> Diferente de outros framework que usam convenções obscuras, a idéia é manter
> claro as coisas que o framework esta fazendo por trás do código
> <br />
> <br />
> Associações 1 para 1 são feitas seguindo o Padrão CRUD (veja na documentação)
