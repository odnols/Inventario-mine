# ![bloco_grama](https://user-images.githubusercontent.com/56841881/129285038-e3f466dd-1d2f-4207-a9c0-da4f353679f0.png) Inventário Minecraft


Este projeto possui a função de levantar estatísticas do inventário do minecraft.

Todos os sprites foram retirados diretamente da Edição Java e possuem como base ela. <br>
( ! ) Para executar é necessário simular um servidor local e subir os dados do JSON em `JSON/Dados_locais.json` ao banco. <br>
Eu utilizei o `wamp`, junto do `mysql` para trabalhar com tudo, não testei com outros programas.

<hr>
O Sistema possuí todos os itens atuais do jogo, e todos os valores dentro do JSON são manipulados e utilizados pelo sistema para criar um menu parecido com o do jogo.
Todas as características e estilos foram recriadas fidedignas ao jogo base na <em>Edição Java</em>. <br><br>

<em>Nota: O Sistema está sempre sendo atualizado, sendo assim, verifique sempre para obter a versão mais atualizada :P</em>


<h2>Pesquisando</h2>
<p>A ferramenta de pesquisa também está presente, você pode procurar por qualquer item ou bloco.</p>
<img src="https://user-images.githubusercontent.com/56841881/129588593-7e889afd-4364-4221-b622-6dc1843c4e09.png">


<p>Também há várias abas extras, para ajudar a gerenciar o sistema ou navegar entre as versões do jogo</p>
<img src="https://user-images.githubusercontent.com/56841881/129588380-b1d86add-41ec-4b23-ac62-28a1cea6bc4c.png">


<h2>Editando</h2>
<p>Para editar os itens, existe uma tela separada, onde vários dados do item podem ser alterados facilmente.</p>
<img src="https://user-images.githubusercontent.com/56841881/129285247-4c226e9f-0770-413f-9955-7060a47e31e9.png">

Por esta tela é possível definir a cor do nome do item, definir a aba que ele irá aparecer no menu, a versão que foi adicionado, seu nome, descrição e outras estatísticas.

* Existem vários valores internos usados para definir as cores dos comentários, abaixo você pode conferir como editar eles//

`[&1` - Cor azul <br>
`[&2` - Cor vermelha <br>
`[&3` - Cor roxa <br>
`[&4` - Cor verde <br><br>

`[&r` - Cor padrão ( cinza ) <br>
`[&s` - Pula uma linha <br><br>

( Todos os `[&` pulam uma linha, com excessão do `[&r` ) <br><br>

* <h3>Exemplos de uso</h3>
-> Descrição de uma Espada: `[&s[&rNa mão principal: [&4Dano por golpe: 8 [&4Velocidade de ataque: 1.6` <br>
O Resultado será este// <br>
<img src="https://user-images.githubusercontent.com/56841881/129587990-8d306780-146f-4b5a-9b8f-248b5a6c2073.png"><br><br>

-> Descrição de uma Poção: `[&1Força (1:00) [&s[&3Efeito aplicado: [&1Dano por golpe: +3` <br>
O Resultado será este// <br>
<img src="https://user-images.githubusercontent.com/56841881/129587820-0105bbe9-aa52-4852-8840-0eeb4b6707bd.png"><br><br>


<h2>Importando e Exportando</h2>
<p>A Importação dos valores e a exportação é realizada através do arquivo JSON, dessa forma, também é possível adicionar itens editando o JSON e clicando para importar no sistema.<br>Só tenha certeza que o ID não é repetido, dessa forma o sistema irá adicionar o item, utilizando o ID informado</p>
<img src="https://user-images.githubusercontent.com/56841881/129283878-c612a002-9603-4f97-bdd4-963b851b8086.png">


<h2>Estatísticas Gerais</h2>
<p>Atendendo o objetivo do sistema, essa parte é onde ficam todos os valores interessantes sobre os itens do jogo, nele temos vários, onde é possível ver de forma concreta a quantidade de itens existentes no jogo, e com tais características.</p>
<img src="https://user-images.githubusercontent.com/56841881/129284048-5f3029e8-1dc1-4fc3-aeeb-5eb539338587.png">


<h2>Modo noturno</h2>
<p>Também há um modo noturno incluso no sistema, ele deixará tudo mais escuro ( :v ) e agradável aos olhos, caso precise/goste de usar este modo.</p>
<img src="https://user-images.githubusercontent.com/56841881/129284931-8d68ba72-18a6-4620-a27e-cb0d2a17af98.png">


<h2>Máquina do tempo</h2>
<p>É possível navegar entre todas as versões completas lançadas até hoje, partindo da Edição Java 1.0 até a versão 1.18. Com a ajuda de um menu separado, é possível navegar de versão em versão, listando a quantidade de itens que foram adicionados em cada uma e destacando-os.</p>
<img src="https://user-images.githubusercontent.com/56841881/129590450-f0cbf2a5-b10e-47c7-bb63-9a5e6a4c9ffe.png">

<p>O Programmer Art também está incluso no sistema, os sprites estão sendo coletados da Edição Java 1.12, para ativar este recurso, basta clicar no canto inferior direito chamado "Programmer Art", todos os itens que foram adicionados antes da 1.13 terão seus gráficos trocados para a versão anterior!</p>
<img src="https://user-images.githubusercontent.com/56841881/129591015-04ce4b83-8bbd-4741-aa6f-94d6a26ca15f.png">

<h3>Menu Clássico</h3>
O Menu Clássico é parte da máquina do tempo, ele é ativado nas Edições 1.0, 1.1 e 1.2; Mais versões de menus serão implementadas no futuro para cobrir todas as alterações que ocorreram no menu do jogo.
<img src="https://user-images.githubusercontent.com/56841881/129591455-95d3f9a2-d03c-4ae5-8a3e-81f87ae59089.png">
