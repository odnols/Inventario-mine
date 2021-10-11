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
<img src="https://user-images.githubusercontent.com/56841881/136726403-035c4929-acf0-4c9b-ba22-dec22d2bee4a.png">


<p>Também há várias abas extras, para ajudar a gerenciar o sistema ou navegar entre as versões do jogo</p>
<img src="https://user-images.githubusercontent.com/56841881/136726469-0b83aafa-7877-4f41-b9d1-074aec0ed4c2.png">


<h2>Editando</h2>
<p>Para editar os itens, existe uma tela separada, onde vários dados do item podem ser alterados facilmente.</p>
<img src="https://user-images.githubusercontent.com/56841881/136726529-87e1dbf7-6cd8-41fa-8794-8efa14122992.png">

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
<img src="https://user-images.githubusercontent.com/56841881/136668949-a82aeba2-674e-425f-afdb-ecd69ee031f7.png">


<h2>Estatísticas Gerais</h2>
<p>Atendendo o objetivo do sistema, essa parte é onde ficam todos os valores interessantes sobre os itens do jogo, nele temos vários, onde é possível ver de forma concreta a quantidade de itens existentes no jogo, e com tais características.</p>
<img src="https://user-images.githubusercontent.com/56841881/136726586-172f91a5-7b97-4270-a1b5-da4ae5b90c3f.png">


<h2>Modo noturno</h2>
<p>Também há um modo noturno incluso no sistema, ele deixará tudo mais escuro ( :v ) e agradável aos olhos, caso precise/goste de usar este modo.</p>
<img src="https://user-images.githubusercontent.com/56841881/136726678-c00180a8-6892-418f-90be-04b3fc225e0c.png">


<h2>Máquina do tempo</h2>
<p>É possível navegar entre todas as versões completas lançadas até hoje, partindo da Edição Java 1.0 até a versão 1.18. Com a ajuda de um menu separado, é possível navegar de versão em versão, listando a quantidade de itens que foram adicionados em cada uma e destacando-os.</p>
<img src="https://user-images.githubusercontent.com/56841881/136726802-5db9d851-b53c-4181-a186-e2f7d87b7c7b.png">

<p>O Programmer Art também está incluso no sistema, os sprites estão sendo coletados da Edição Java 1.12 e 1.11, para ativar este recurso, basta clicar no canto inferior direito chamado "Programmer Art", todos os itens que possuírem texturas antigas terão seus sprites alterados para a versão anterior!</p>
<img src="https://user-images.githubusercontent.com/56841881/129591015-04ce4b83-8bbd-4741-aa6f-94d6a26ca15f.png">

<h3>Menu Clássico</h3>
O Menu Clássico é parte da máquina do tempo, ele é ativado nas Edições 1.0, 1.1 e 1.2; Mais versões de menus serão implementadas no futuro para cobrir todas as alterações que ocorreram no menu do jogo.
<img src="https://user-images.githubusercontent.com/56841881/136726929-fe675898-fc13-4869-8ec6-4e24ab71c238.png">
