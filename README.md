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
<img src="https://user-images.githubusercontent.com/56841881/129285220-ef0cf3cc-a8df-4424-80bb-13241003bb62.png" alt="accessibility text">

<p>Também há várias abas extras, para ajudar a gerenciar o sistema ou navegar entre as versões do jogo</p>
<img src="https://user-images.githubusercontent.com/56841881/129285233-456852fa-5c44-483f-bb83-02b0d28c6820.png" alt="accessibility text">


<h2>Editando</h2>
<p>Para editar os itens, existe uma tela separada, onde vários dados do item podem ser alterados facilmente.</p>
<img src="https://user-images.githubusercontent.com/56841881/129285247-4c226e9f-0770-413f-9955-7060a47e31e9.png" alt="accessibility text">

Por esta tela é possível definir a cor do nome do item, definir a aba que ele irá aparecer no menu, a versão que foi adicionado, seu nome, descrição e outras estatísticas.

* Existem vários valores internos usados para definir as cores dos comentários, abaixo você pode conferir como editar eles//

`[&1` - Cor azul <br>
`[&2` - Cor vermelha <br>
`[&3` - Cor roxa <br>
`[&4` - Cor verde <br><br>

`[&r` - Cor padrão ( cinza ) <br>
`[&s` - Pula uma linha <br><br>

( Todos os `[&` pulam uma linha, com excessão do `[&r` ) <br><br>

* <h3>Exemplos de uso<h3>
-> Descrição de umaEspada = `[&s[&rNa mão principal: [&4Dano por golpe: 8 [&4Velocidade de ataque: 1.6` <br>
O Resultado será este// <br>
<img src="https://user-images.githubusercontent.com/56841881/129284333-cd3ef660-0e51-4f40-9e42-8742af529bca.png" alt="accessibility text"><br><br>

-> Descrição de uma Poção = `[&1Força (1:00) [&s[&3Efeito aplicado: [&1Dano por golpe: +3` <br>
O Resultado será este// <br>
<img src="https://user-images.githubusercontent.com/56841881/129284575-87664d82-9ad3-4d5c-93e5-0099f80300b8.png" alt="accessibility text"><br><br>


<h2>Importando e Exportando</h2>
<p>A Importação dos valores e a exportação é realizada através do arquivo JSON, dessa forma, também é possível adicionar itens editando o JSON e clicando para importar no sistema.<br>Só tenha certeza que o ID não é repetido, dessa forma o sistema irá adicionar o item, utilizando o ID informado</p>
<img src="https://user-images.githubusercontent.com/56841881/129283878-c612a002-9603-4f97-bdd4-963b851b8086.png" alt="accessibility text">


<h2>Estatísticas Gerais</h2>
<p>Atendendo o objetivo do sistema, essa parte é onde ficam todos os valores interessantes sobre os itens do jogo, nele temos vários, onde é possível ver de forma concreta a quantidade de itens existentes no jogo, e com tais características.</p>
<img src="https://user-images.githubusercontent.com/56841881/129284048-5f3029e8-1dc1-4fc3-aeeb-5eb539338587.png" alt="accessibility text">


<h2>Modo noturno</h2>
<p>Também há um modo noturno incluso no sistema, ele deixará tudo mais escuro ( :v ) e agradável aos olhos, caso precise/goste de usar este modo.</p>
<img src="https://user-images.githubusercontent.com/56841881/129284931-8d68ba72-18a6-4620-a27e-cb0d2a17af98.png" alt="accessibility text">