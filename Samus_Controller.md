# Samus\_Controller #

> Classes de Controle - Samus\_Controller(controlador)
> <br />
> As modelos(classes Samus\_Model) são nossos modelos queridos, mas são os controladors(classes Samus\_Controller)
> que botam pra fazer, nossos controladors também são ótimos reprodutores, gerando
> instancias das nossas modelos para todo mundo ver. Mas nossos controladors são educados
> e cuprem corretamente o que o Fazendeiro(Samus\_Keeper) mandam ele fazer.
> <br />
> <br />
> As classes Samus\_Controller funcionam assim: qualquer propriedade da classe que estiver
> ecapsulada (com seu getter e setter) poderá ser utilizado no contexo da
> visão caso ela tenhao seu getter especificado (private $nome | getNome()),
> caso o atributo seja publico ele também poderá ser utilizado no contexto da
> visão. O arquivo de visão deve estar no diretório das views e deve ter o
> mesmo nome da classe Samus\_Controller, e importante a classe Samus\_Controller deve implementar o método
> index() (que faz parte da interface SamusController) este método é executado
> sempre que a visão associada é chamada: <br />
> Ex.:<br />
> Controlador: <br />
> classes/controls/Conteudo.php <br />
> class Conteudo extends Samus\_Controller {<br />
> > private $nome;<br />
> > public  $valor = "um valor qualquer";

> <br />
> > public function index() {<br />
> > $this->setNome("Vinicius Fiorio Custódio");<br />
> > }<br />

> <br />
> > public function getNome() {<br />
> > > return $this->nome;<br />

> > }<br />

> <br />
> > public function setNome($nome) {<br />
> > > $this->nome = $nome;<br />

> > }<br />

> <br />
> > }<br />


> <br />
> <br />
> Visão: <br />
> views/conteudo.tpl <br />
> ...${ $nome } is ${ $valor }..<br />
> <br />


# Details #

http://www.samus.com.br