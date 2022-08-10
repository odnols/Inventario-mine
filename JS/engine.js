// Variavéis Globais 
var prancheta = false, alvo_anterior = null, inicio = 0, posicao_scroll = 0, libera_scroll = 1, cache_pesquisa = null, itens_genericos = 0, itens_ocultos = 0, tema = null, pesquisa = 0;

var qtd_itens = 0, qtd_itens_colet = 0, qtd_itens_renov = 0, qtd_itens_empil = 0, qtd_itens_colet_empil = 0, qtd_itens_colet_empil_n_renov = 0, qtd_itens_n_empil = 0;

var graphics = 0, total_load = 50, dados_globais;
var dados_itens = [], itens_atalho = [], array_crafting = [null, null, null, null, null, null, null, null, null];

function gerencia_scroll(valor){
    libera_scroll = valor;
}

function scrollSincronizado(principal, sincronizado, versao_jogo){

    let elem = document.getElementById(sincronizado);
    let doc = document.getElementById(principal);

    if(principal == "listagem"){
        if(libera_scroll){
            if(versao_jogo > 2 || typeof versao_jogo == "undefined")
                value = parseInt(86 * doc.scrollTop / (doc.scrollHeight - doc.clientHeight));
            else
                value = parseFloat(90.7 * doc.scrollTop / (doc.scrollHeight - doc.clientHeight));

            elem.style.top = value + '%';
        }
    }else{
        if(versao_jogo > 2 || typeof versao_jogo == "undefined"){
            porcentagem = parseInt(86 * posicao_scroll / (elem.scrollHeight - elem.clientHeight));
            porcentagem = porcentagem - 59;
        }else{
            porcentagem = parseFloat(90.9 * posicao_scroll / (elem.scrollHeight - elem.clientHeight));
            porcentagem = porcentagem - 90;
        }

        value = parseInt((elem.scrollHeight / 180) * porcentagem);
        elem.scrollTop = value;        
    }
}

function abre_menu(valor){
    if(valor == 1){
        $("#menu_completo").fadeOut();
        $("#prancheta_add").fadeToggle();
    }else if(valor == 2){
        clique('prancheta', 1);
        $("#prancheta_add").fadeOut();
        $("#menu_completo").fadeToggle();
    }else{
        $("#menu_completo").fadeOut();
        $("#prancheta_add").fadeOut();
    }
}

function clique(valor, estado){

    if(typeof valor == "string"){

        let mostra = document.getElementsByClassName("Prancheta");

        if(!prancheta && estado != 1){
            document.getElementById("prancheta_add").style.display = "Block";
            mostra[0].style.display = "Block";
            prancheta = true;
        }else{
            document.getElementById("prancheta_add").style.display = "None";
            mostra[0].style.display = "None";
            prancheta = false;
        }
    }else{
        if(typeof event !== "undefined"){

            let tecla = event.keyCode;

            switch(tecla){
                case 101: // e
                case 69 : // E
                    alvo = "prancheta_add";

                    if(!menu)
                        alvo = "menu_completo";
                break;   
                case 119: // q
                case 87 : // Q
                    alvo = "prancheta_add";
                    menu = !menu;
                break;
            }
        }
    }
}

function categoria(alvo, local){

    if(local == 2){
        local = 1;

        alvo = alvo.toString();
        
        document.getElementById("barra_pesquisa_input").value = alvo;
    }
    
    if(alvo == null)
        alvo = 10;

    if(alvo == 10){
        pesquisa = 1;

        let texto = "";

        if(document.getElementById("barra_pesquisa_input"))
            texto = document.getElementById("barra_pesquisa_input").value;
        // Menu clássico
            // return;

        document.getElementById("titulo_aba").innerHTML = "Buscar";        

        if(texto.length > 0){
            texto = texto.toLowerCase();
            alvo = texto;
            local = 1;
        }
    }

    let categorias = ["Construcao", "Decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Pocoes", "Especiais", "Pesquisa"];
    let versoes = ["1.0", "1.1", "1.2", "1.3", "1.4", "1.5", "1.6", "1.7", "1.8", "1.9", "1.10", "1.11", "1.12", "1.13", "1.14", "1.15", "1.16", "1.17", "1.18", "1.19"];

    let itens = 0, alvos;

    if((categorias[alvo]) && (categorias[alvo] !== "Pesquisa" && local !== 1)) // Descrição das abas do inventário para os itens
        pesquisa = 0;

    if(typeof alvo == "string" && alvo.length == 0){
        local = 0;
        alvo = 10;
    }
    
    if(alvo == "off" || alvo == "não_coletável" || alvo == "generico" || alvo == "oculto")
        verifica_posicao(alvo);
    else if(document.getElementById("img_configs_2")){
        document.getElementById("img_configs_2").style.display = "none";
        document.getElementById("img_coletaveis_2").style.display = "none";
        document.getElementById("img_genericos_2").style.display = "none";
    }

    if(local == 0){ // Definindo a aba alvo
        alvos = document.getElementsByClassName(categorias[alvo]);

        if(document.getElementById("img_versoes_2")){
            document.getElementById("img_versoes_2").style.display = "none";
            document.getElementById("lista_versoes").style.display = "none";
        }
    }else
        alvos = document.getElementsByClassName(alvo);

    if((versoes.includes(alvo) || categorias.includes(categorias[alvo])) &&  (itens_genericos || itens_ocultos)){ // Esconde todos os itens genéricos
        
        if(itens_genericos) mostrar_genericos();

        if(itens_ocultos) mostrar_ocultos();

        cache_pesquisa = null;
        document.getElementById("barra_pesquisa_input").value = "";
    }

    if((versoes.includes(alvo) || categorias.includes(categorias[alvo])) && cache_pesquisa != null) // Limpa o cache de pesquisa
        cache_pesquisa = null;
    
    // Escondendo todos os itens de todas as categorias
    for(let i = 0; i < categorias.length; i++){
        let esconde = document.getElementsByClassName(categorias[i]);

        if(typeof alvo !== "string"){
            for(let x = 0; x < esconde.length; x++){
                if(alvo != 10)
                    esconde[x].style.display = "None";
            }
        }else{
            for(let x = 0; x < esconde.length; x++){
                if(typeof esconde[x] !== "undefined"){
                    esconde[x].style.display = "None";

                    if(typeof alvo == "string"){
                        // esconde[0].style.display = "Block";
                        
                        if(i == 10){
                            esconde[1].style.display = "Block";
                            esconde[2].style.display = "Block";
                            esconde[3].style.display = "Block";
                            esconde[4].style.display = "Block";
                            esconde[5].style.display = "Block";

                            if(esconde.length > 6)
                                esconde[6].style.display = "Block";
                        }
                    }
                }else{
                    if(typeof esconde[x] !== "undefined"){
                        esconde[x].style.display = "Block";
                        itens++;
                    }
                }
            }

            ordena_guias_ativas(categorias, alvos);
        }
    }

    // Itens genéricos
    if(itens_genericos == 1)
        alvos = document.getElementsByClassName("Generico");

    // Exibindo os itens da categoria escolhida
    for(let i = 0; i < alvos.length; i++){
        alvos[i].style.display = "Block";
    }

    let slots_livres = ((alvos.length - 1) % 9);

    if(((alvos.length - 1) % 9 != 0) || alvos.length < 45){
        if(alvos.length < 45 && itens < 45)
            if(typeof alvo !== "string")
                slots_livres = 46 - alvos.length;
            else
                slots_livres = 45 - alvos.length;
        else
            if(alvo === 10)
                slots_livres = (alvos.length % 9) - 9;
            else
                slots_livres = 9 - (alvos.length % 9);

        if(slots_livres == 9)
            slots_livres = 0;
    }

    if(slots_livres > 0){
        document.getElementById("complementa_slots").innerHTML = "";
        
        for(let j = 0; j < slots_livres; j++)
            document.getElementById("complementa_slots").innerHTML += "<div class='slot_item'></div>";
    }else // Limpa os slots de outras abas
        document.getElementById("complementa_slots").innerHTML = "";
    
    if(versoes.includes(alvo)){
        $("#versao_referencia").fadeIn();
        document.getElementById("num_referencia").innerHTML = `${alvo} ( ${alvos.length} )`;
    }else
        $("#versao_referencia").fadeOut();

    if(itens > 45 || alvos.length > 45){
        document.getElementById("barra_scroll").style.display = "Block";
        document.getElementById("barra_scroll_block").style.display = "None";
    }else{
        document.getElementById("barra_scroll_block").style.display = "Block";
        document.getElementById("barra_scroll").style.display = "None";
    }

    // Definindo o nome da guia seleciona
    if(alvo == 0)
        nome_aba = "Blocos de construção";
    else if(alvo == 1)
        nome_aba = "Blocos decorativos";
    else if(alvo == 8)
        nome_aba = "Poções"
    else if(alvo == 9)
        nome_aba = "Itens especiais";
    else if(alvo == 10)
        nome_aba = "Buscar";
    else
        nome_aba = categorias[alvo];

    if(typeof alvo != "string")
        document.getElementById("titulo_aba").innerHTML = nome_aba;
}

function ordena_guias_ativas(categorias, alvos){

    let guias = [];

    // Pesquisa sem inserção
    if(alvos.length < 1) {
        for(let i = 0; i < categorias.length; i++){
            let alvos_mostra = document.getElementsByClassName(categorias[i]);

            if(alvos_mostra.length > 1){
                for(let x = 0; x < alvos_mostra.length; x++)
                    alvos_mostra[x].style.display = "Block";
                
                if(!guias.includes(categorias[i])) guias.push(categorias[i]);
            }
        }
    }else{
        // Listando guias com itens ativos
        for(let i = 0; i < alvos.length; i++ ){
            if(!guias.includes(alvos[i].classList[1]))
                guias.push(alvos[i].classList[1])
        }
    }

    // Desabilitando todas as guias
    for(let i = 0; i < categorias.length; i++){
        let guia_alvo = document.getElementsByClassName(`aba_menu_${categorias[i]}`);

        if(guia_alvo.length > 0) guia_alvo[0].style.display = "None";
    }

    for(let i = 0; i < guias.length; i++){
        let guia_alvo = document.getElementsByClassName(`aba_menu_${guias[i].toLowerCase()}`);
        if(guia_alvo.length > 0) guia_alvo[0].style.display = "Block";
    }
}

function mostrar_genericos(){
    let alvos = document.getElementsByClassName('Generico');

    if(itens_genericos == 0){
        for(let i = 0; i < alvos.length; i++)
            alvos[i].style.display = "Block";

        itens_genericos = 1;
    }else{
        for(let i = 0; i < alvos.length; i++)
            alvos[i].style.display = "None";
        
        document.getElementById("img_genericos_2").style.display = "none";

        itens_genericos = 0;
    }
}

function mostrar_ocultos(){
    let alvos = document.getElementsByClassName('oculto');

    if(itens_ocultos == 0){
        for(let i = 0; i < alvos.length; i++)
            alvos[i].style.display = "Block";

        itens_ocultos = 1;
    }else{
        for(let i = 0; i < alvos.length; i++)
            alvos[i].style.display = "None";
        
        document.getElementById("img_ocultos_2").style.display = "none";

        itens_ocultos = 0;
    }
}

function filtra_pesquisa(){
    texto = document.getElementById("barra_pesquisa_input").value;
    texto = texto.toLowerCase();

    categoria(texto, 1);
}

function filtragem_automatica(alvo_filtragem, local){

    if(local != null)
        document.getElementById("lista_versoes").style.display = "none";
    
    if(alvo_filtragem == "genéricos")
        alvo_filtragem = "generico";

    // Verifica se a requisição é igual a anterior e desabilita o elemento
    if(cache_pesquisa == "off" || cache_pesquisa == "não_coletável" || cache_pesquisa == "generico" || cache_pesquisa == "oculto"){
        if(cache_pesquisa != alvo_filtragem){
            cache_pesquisa = alvo_filtragem;
            document.getElementById("barra_pesquisa_input").value = alvo_filtragem;
            verifica_posicao(alvo_filtragem);
        }else{
            document.getElementById("barra_pesquisa_input").value = "";
            cache_pesquisa = null;
        }
    }else{
        cache_pesquisa = alvo_filtragem;

        document.getElementById("barra_pesquisa_input").value = alvo_filtragem;

        verifica_posicao(alvo_filtragem);
    }

    if(alvo_filtragem == "generico" || (alvo_filtragem != "generico" && itens_genericos == 1))
        mostrar_genericos();

    if(alvo_filtragem == "oculto" || (alvo_filtragem != "oculto" && itens_ocultos == 1))
        mostrar_ocultos();

    filtra_pesquisa();
}

function lista_versoes(){
    $("#lista_versoes").toggle();
    $("#img_versoes_2").toggle();
}

dragElement(document.getElementById("barra_scroll"));

function dragElement(elmnt) {

    if(elmnt != null){
        let pos2 = 0, pos4 = 0;
        
        if(document.getElementById(elmnt.id))
            document.getElementById(elmnt.id).onmousedown = dragMouseDown;
        else
            elmnt.onmousedown = dragMouseDown;
        
        function dragMouseDown(e) {
            e = e || window.event;
            e.preventDefault();
            // get the mouse cursor position at startup:
            pos4 = e.clientY;
            document.onmouseup = closeDragElement;
            // call a function whenever the cursor moves:
            document.onmousemove = elementDrag;
        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();
            // calculate the new cursor position:
            pos2 = pos4 - e.clientY;
            pos4 = e.clientY;

            // set the element's new position:
            scrollSincronizado('barra_rolagem', 'listagem');
            
            if(pos4 > 185 && pos4 < 484){
                elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
                posicao_scroll = pos4;
            }
            
            if(elmnt.offsetTop - pos2 < 0)
                elmnt.style.top = 0 + "px";

            if(elmnt.offsetTop - pos2 > 286)
                elmnt.style.top = 286 + "px";
        }

        function closeDragElement() {
            /* stop moving when mouse button is released:*/
            document.onmouseup = null;
            document.onmousemove = null;
        }
    }
}

function verifica_posicao(caso){

    if(caso == "off")
        caso = 0;
    
    if(caso == "não_coletável")
        caso = 1;

    if(caso == "generico")
        caso = 2;

    if(caso == "oculto")
        caso = 3;

    alvos = ["img_configs", "img_coletaveis", "img_genericos", "img_ocultos"];
    alvos_finais = ["img_configs_2", "img_coletaveis_2", "img_genericos_2", "img_ocultos_2"];

    elemento = document.getElementById(alvos[caso]);
    posicao = elemento.getBoundingClientRect();
    
    for(let i = 0; i < alvos_finais.length; i++){
        document.getElementById(alvos_finais[i]).style.display = "none";
    }

    if(caso != 3){
        if(posicao.x > 622)
            document.getElementById(alvos_finais[caso]).style.animation = "none";
        else
            document.getElementById(alvos_finais[caso]).style.animation = "puxa_campos .5s";
    }else{
        if(posicao.y > 510)
            document.getElementById(alvos_finais[caso]).style.animation = "none";
        else
            document.getElementById(alvos_finais[caso]).style.animation = "puxa_campos2 .5s";
    }

    document.getElementById(alvos_finais[caso]).style.display = "block";
}

function exibe_detalhes_item(id_item){
    window.location = `item_detalhes.php?id=${id_item}`;
}

function exibe_item(caminho_sprite){
    localStorage.setItem("sprite_escolhido", caminho_sprite);
    window.location = `./sprite.html`;
}

function voltar_pag(){
    window.location = "index.php";
}

function previewImage(local) {
    let file = document.getElementById("input_img").files;

    // Criando o nome interno automaticamente
    nome_interno = file[0].name;
    nome_interno = nome_interno.replace('.png', "");

    if(local == 0)
        document.getElementById("barra_nome_interno_pr").value = nome_interno;
    else
        document.getElementById("barra_nome_interno").value = nome_interno;

    if(file.length > 0) {
        let fileReader = new FileReader();

        fileReader.onload = function (event){
            document.getElementById("preview_sprite").setAttribute("src", event.target.result);
        };

        fileReader.readAsDataURL(file[0]);
    }
}

function apagarItem(id_item){
    if(confirm("Deseja realmente remover?"))
        window.location = `../PHP/item_remover.php?id=${id_item}`;
}

function troca_tema(versao_jogo, local){

    if(tema == null || tema == 1)
        localStorage.setItem('tema_invent_mc', "0");
    
    if(tema == 0)
        localStorage.setItem('tema_invent_mc', "1");

    sincroniza_tema(versao_jogo, local);
}

function sincroniza_tema(versao_jogo, local){

    tema = localStorage.getItem("tema_invent_mc");
    let alvo = "claro";
    let caminho = "";

    if(local) caminho = "../";
    
    if(tema != null)
        tema = parseInt(tema);
    else
        tema = 1;
    
    if(tema == 0){
        alvo = "escuro";
        document.getElementById("icone_tema").innerHTML = "🌑";
        document.getElementById("bttn_troca_tema").style.backgroundColor = "rgb(39, 39, 45)";
        document.getElementById("filtro_colorido").style.background = "linear-gradient(0deg, rgba(0,0,0,0.9248074229691877) 0%, rgba(3, 26, 124, 0.67) 39%, rgba(0,212,255,0) 100%)";
    }

    if(alvo == "claro"){
        document.getElementById("icone_tema").innerHTML = "☀️";
        document.getElementById("bttn_troca_tema").style.backgroundColor = "white";
        document.getElementById("filtro_colorido").style.background = "linear-gradient(0deg, rgba(0,0,0,0.9248074229691877) 0%, rgba(9,121,99,0.6699054621848739) 39%, rgba(0,212,255,0) 100%)";
    }

    lista_templates = ["#prancheta_add", ".input_prancheta", "#barra_pesquisa_input", "#barra_scroll", ".slot_item", ".slot_item_add", "#menu_criacao", "#seta_direita_crafting", "#seta_esquerda_crafting", "#craft_prancheta", "#slot_craft_ativo", "#preview_item_craft", "#grid_crafting", ".slot_item_crafting"];
    nome_template = ["prancheta.png", "barra_prancheta.png", "barra_pesquisa.png", "scroll.png", "slot.png", "slot_add.png", "crafting.png", "seta_direita.png", "seta_esquerda.png", "craft.png", "slot_add.png", "grid_crafting.png", "grid_crafting.png", "slot_item_crafting.png"];

    lista_imagens = ["prancheta", "img_construcao", "img_decorativos", "img_redstone", "img_transportes", "img_diversos", "img_alimentos", "img_ferramentas", "img_combate", "img_pocoes", "img_especiais", "img_pesquisa", "barra_scroll_block", "menu", "img_ocultos", "img_craft_todos", "img_craft_ferramentas", "img_craft_blocos", "img_craft_diversos", "img_craft_redstone"];
    nome_arquivos = ["prancheta.png", "aba_construcao.png", "aba_decorativos.png", "aba_redstone.png", "aba_transportes.png", "aba_diversos.png", "aba_alimentos.png", "aba_ferramentas.png", "aba_combate.png", "aba_pocoes.png", "aba_especiais.png", "aba_pesquisa.png", "scroll_bloqueado.png", "menu.png", "aba_oculto.png", "aba_craft_todos.png", "aba_craft_ferramentas.png", "aba_craft_blocos.png", "aba_craft_diversos.png", "aba_craft_redstone.png"];

    if(typeof versao_jogo !== "undefined")
        if(versao_jogo <= 2){
            nome_arquivos[nome_arquivos.indexOf("menu.png")] = "menu_classic.png";
            
            document.getElementById("titulo_aba").innerHTML = "Seleção de item";

            document.getElementById("titulo_aba").style.top = "90%";
            document.getElementById("listagem").style.width = "433px";
            document.getElementById("listagem").style.height = "486px";
            document.getElementById("menu_completo").style.top = "0px";
            document.getElementById("menu_completo").style.left = "7%";
            document.getElementById("lista_itens").style.left = "12px";
            document.getElementById("lista_itens").style.top = "-69px";
            document.getElementById("lista_itens").style.width = "433px";
            document.getElementById("lista_itens").style.height = "486px";
            document.getElementById("barra_rolagem").style.top = "69px";
            document.getElementById("barra_rolagem").style.right = "111px";
            document.getElementById("barra_rolagem").style.height = "480px";
        }
    else{
        botoes_menu = document.getElementsByClassName("botoes_menu");

        for(let i = 0; i < botoes_menu.length; i++){
            botoes_menu[i].style.display = "Block";
        }
    }
        
    for(let i = 0; i < lista_imagens.length; i++){ // Imagens
        imagem = document.getElementById(lista_imagens[i]);
        
        if(imagem != null)
            imagem.src = `${caminho}IMG/Interface/${alvo}/${nome_arquivos[i]}`;
    }

    for(let i = 0; i < lista_templates.length; i++){
        if(lista_templates[i].includes("#")){ // Elementos que utilizam ID

            lista_templates[i] = lista_templates[i].replace("#", ""); 
            
            objeto = document.getElementById(lista_templates[i]);
            if(objeto != null)
                objeto.style.background = `url('${caminho}IMG/Interface/${alvo}/${nome_template[i]}') no-repeat`;
        }else{ // Elementos que estão referenciados como classes

            lista_templates[i] = lista_templates[i].replace(".", "");
            let alvos = document.getElementsByClassName(lista_templates[i]);

            for(let j = 0; j < alvos.length; j++)
                alvos[j].style.background = `url('${caminho}IMG/Interface/${alvo}/${nome_template[i]}') no-repeat`;
        }
    }

    const textos = document.getElementsByClassName("cor_textos");
    const textos_craft = document.getElementsByClassName("textos_craft");
    const tam_textos = textos.length || textos_craft.length
    const cores_texto = ["white", "#3f3f3f"];
    const alvos = textos || textos_craft;
    const titulo_aba = document.getElementById("titulo_aba");

    if(tema == 0 && titulo_aba)
        titulo_aba.style.color = "#ffffff";
    else if(titulo_aba)
        titulo_aba.style.color = "#3f3f3f";

    for(let i = 0; i < tam_textos; i++)
        alvos[i].style.color = cores_texto[tema];
}

function troca_itens(pag){

    const esconde1 = document.getElementsByClassName("pag_1");
    esconde1[0].style.display = "None";
    
    const esconde2 = document.getElementsByClassName("pag_2");
    esconde2[0].style.display = "None";

    document.getElementById("pag_1").style.display = "None";
    document.getElementById("pag_2").style.display = "None";

    const alvo = document.getElementsByClassName("pag_"+ pag);
    alvo[0].style.display = "Block";

    document.getElementById("pag_"+ ( 3 - pag)).style.display = "Block";
}

function toolTip(nome, descricao, nome_interno, cor_item, local){

    if(typeof nome != "undefined"){

        id_nome_item = "nome_item_minetip";
        id_descricao_item = "descricao_item_minetip";
        id_nome_interno = "nome_interno_minetip";

        if(typeof local !== "undefined"){
            id_nome_item = "nome_item_minetp";
            id_descricao_item = "descricao_item_minetp";
            id_nome_interno = "nome_interno_minetp";
        }

        const alvo_tooltip = document.getElementById(id_nome_item);
        const alvo_id_descricao_item = document.getElementById(id_descricao_item);

        if(alvo_tooltip) alvo_tooltip.innerHTML = "";
        if(alvo_id_descricao_item) alvo_id_descricao_item.innerHTML = "";

        const itens_especiais = ["", "item_encantado", "item_especial", "item_lendario"];
        const cores_efeitos = ["&1", "&2", "&3", "&4", "&r"];
        const nome_cores_efeitos = ["efeito_cor_azul", "efeito_cor_vermelha", "efeito_cor_roxa", "efeito_cor_verde", "efeito_cor_padrao"];

        if(alvo_tooltip)
            if(cor_item > 0)
                document.getElementById(id_nome_item).innerHTML += `<span class='${itens_especiais[cor_item]}'>${nome}</span>`;
            else
                document.getElementById(id_nome_item).innerHTML = nome;
        
        if(typeof descricao !== "undefined"){
            if(descricao.indexOf("&") == -1){
                document.getElementById(id_descricao_item).style.color = "#a8a8a8";
                document.getElementById(id_descricao_item).style.textShadow = "3px 3px 0 #2a2a2a";

                if(typeof descricao !== "undefined")
                    document.getElementById(id_descricao_item).innerHTML = descricao;
                else
                    document.getElementById(id_descricao_item).innerHTML = "";
            }else{

                alvos_replace = ["Construcao", "Decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Pocoes", "Especiais", "Generico"];

                if(pesquisa == 0){
                    for(let i = 0; i< alvos_replace.length; i++)
                        descricao = descricao.replace(`[&1${alvos_replace[i]}`, "");
                }else{
                    categorias_exib = ["Blocos de construção", "Blocos decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Poções", "Especiais", "Genérico"];

                    alvo_alteracao = descricao.split(" ");
                    alvo_alteracao[0] = alvo_alteracao[0].replace("[&1", "");

                    let i = alvos_replace.indexOf(alvo_alteracao[0]);
                    descricao = descricao.replace(alvo_alteracao[0], `[&1${categorias_exib[i]}`);
                }

                descricao_colorida = descricao.split("[");

                for(let j = 0; j < descricao_colorida.length; j++){
                    for(let i = 0; i < cores_efeitos.length; i++){
                        if(descricao_colorida[j].indexOf(cores_efeitos[i]) != -1){

                            descricao_colorida[j] = descricao_colorida[j].replace(cores_efeitos[i], "", 1);
                            
                            document.getElementById(id_descricao_item).innerHTML += "<span class='"+ nome_cores_efeitos[i] +"'> "+ descricao_colorida[j] +" </span><br>";

                            break;
                        }

                        if(descricao_colorida[j] == "&s")
                            document.getElementById(id_descricao_item).innerHTML += "<div class='espaco'></div><br>";
                    }
                }
            }
        }

        if(typeof nome_interno !== "undefined")
            document.getElementById(id_nome_interno).innerHTML = `minecraft:${nome_interno}`;
        else
            document.getElementById(id_nome_interno).innerHTML = "";

        if(typeof local === "undefined")
            document.getElementById("minetip-tooltip").style.display = "Block";

        // Verifica se existem muitos alvos para poder acompanhar o mouse            
        const verifica = document.getElementsByClassName("slot_item");
        const verifica_2 = document.getElementsByClassName("slot_item_crafting");

        if(verifica.length > 1 || verifica_2.length > 1){
            $(".slot_item").on("mousemove", function( event ) {
                $("#minetip-tooltip").css({left:event.pageX + 30, top:event.pageY - 50} )
            });

            $(".slot_item_add").on("mousemove", function( event ) {
                $("#minetip-tooltip").css({left:event.pageX + 30, top:event.pageY - 50} )
            });

            $(".slot_item_crafting").on("mousemove", function( event ) {
                $("#minetip-tooltip").css({left:event.pageX + 30, top:event.pageY - 50} )
            });
        }
    }else
        document.getElementById("minetip-tooltip").style.display = "None";
}

function sincroniza_itens(receita, produto, qtd, local, id_item){

    fetch('https://raw.githubusercontent.com/odnols/inventario-mine/main/Files/JSON/dados_locais.json')
    .then(response => response.json())
    .then(async res_artigo => {
        
        dados_itens = res_artigo;

        if(local !== 1)
            mostra_crafting(receita, produto, qtd, 1);
        else
            seleciona_item(id_item);
    });
}

function mostra_crafting(receita, produto, qtd, local){

    let caminho = '';
    if(local == 2 || local){
        if(local == 2)
            document.getElementById("array_craft").value = receita;
        
        array_crafting = receita.split(",");
    }

    // Converte a string para um array legível
    if(receita.length == 9)
        receita = receita.split(",");

    if(local || local == 2)
        caminho = '../';
    
    const grid = document.getElementsByClassName('grid_craft');
    let sprite_produto = "";
    let dados_produto = "";

    if(dados_itens.length < 1)
        return sincroniza_itens(receita, produto, qtd);

    for(let i = 0; i < grid.length; i++){

        let sprite_item = "";

        if(array_crafting[i] !== null || typeof array_crafting[i] !== "undefined"){
            Object.keys(dados_itens).forEach(function(k){

                if(dados_itens[k]["id_item"] == array_crafting[i])
                    sprite_item = `${caminho}IMG/Itens/new/${dados_itens[k]["tipo_item"]}/${dados_itens[k]["nome_icon"]}`;

                if(dados_itens[k]["id_item"] == produto)
                    sprite_produto = `${caminho}IMG/Itens/new/${dados_itens[k]["tipo_item"]}/${dados_itens[k]["nome_icon"]}`;
            });
            
            if(typeof array_crafting[i] !== "undefined" && sprite_item.length > 0) // Altera o sprite para o item do grid
                grid[i].style.backgroundImage = `url('${sprite_item}')`;
        }else
            grid[i].style.backgroundImage = 'None';
    }

    Object.keys(dados_itens).forEach(function(k){
        if(dados_itens[k]["id_item"] == produto){

            dados_produto = dados_itens[k];

            sprite_produto = `${caminho}IMG/Itens/new/${dados_itens[k]["tipo_item"]}/${dados_itens[k]["nome_icon"]}`;
            nome_produto = dados_itens[k]["nome"];
        }
    });

    const slots_atalho = document.getElementById("slots_atalho_itens");

    if(slots_atalho){
        slots_atalho.innerHTML = "";

        for(let i = 9 - itens_atalho.length; i > 0; i--){
            slots_atalho.innerHTML += `<div class='slot_item'></div>`;
        }
    }

    if(produto !== null){
        if(document.getElementById("click_abrir_crafting"))
            document.getElementById("click_abrir_crafting").setAttribute('onClick', `inicia_craft(${produto})`);

        document.getElementById("sprite_produto").innerHTML = "";
        document.getElementById("sprite_produto").innerHTML += `<img src='${sprite_produto}' style='width: 48px'>`;

        document.getElementById("sprite_produto").setAttribute('onMouseOver', `toolTip("${dados_produto.nome_item}", "${dados_produto.descricao}", "${dados_produto.nome_interno}", null)`);
        document.getElementById("sprite_produto").setAttribute('onMouseOut', `toolTip()`);

        // document.getElementById("qtd_produto").innerHTML = typeof qtd !== "undefined" ? qtd : null;
    }
}

function expande_sprite(caminho){
    window.location.href = `./sprite.php?caminho=${caminho}`;
}

function inicia_craft(id_item){
    window.location = `../modules/craft.php?id=${id_item}`;
}

function seleciona_item(id_item){
    
    let novo_array = [];

    let slots_atalho = document.getElementById("slots_atalho_itens");
    slots_atalho.innerHTML = "";

    if(id_item !== "auto"){
        if(itens_atalho.includes(id_item)) // Removendo o item do elemento
            itens_atalho.splice(itens_atalho.indexOf(id_item), 1);
        else if(itens_atalho.length > 8)
            itens_atalho.pop();
    
    // Adicionando o item no primeiro lugar do array
        novo_array = [id_item].concat(itens_atalho);
        itens_atalho = novo_array;
    }

    if(dados_itens.length < 1)
        return sincroniza_itens(null, null, null, 1, id_item);

    if(id_item == "auto") // Utilizado para sincronizar os dados
        return;

    for(let i = 0; i < itens_atalho.length; i++){

        let slot_craft_ativo = "";

        Object.keys(dados_itens).forEach(function(k){
            if(itens_atalho[i] == dados_itens[k]["id_item"]){
                sprite_item = `../IMG/Itens/new/${dados_itens[k]["tipo_item"]}/${dados_itens[k]["nome_icon"]}`;
                
                if(i == 0)
                    slot_craft_ativo = "id='slot_craft_ativo'";

                slots_atalho.innerHTML += `<div class='slot_item' ${slot_craft_ativo} onclick='seleciona_item(${itens_atalho[i]})'>
                    <img class='icon_item' src='${sprite_item}'>
                </div>`;
            }
        });
    }

    for(let i = 9 - itens_atalho.length; i > 0; i--){
        slots_atalho.innerHTML += `<div class='slot_item'></div>`;
    }

    sincroniza_tema(undefined, 1);
}

function seta_item_craft(indice){

    let grid_craft = document.getElementsByClassName("grid_craft");

    for(let i = 0; i < 9; i++){
        if(i == indice){
            if(grid_craft[i].style.backgroundImage == "")
                Object.keys(dados_itens).forEach(function(k){
                    if(itens_atalho[0] == dados_itens[k]["id_item"]){
                        sprite_item = `../IMG/Itens/new/${dados_itens[k]["tipo_item"]}/${dados_itens[k]["nome_icon"]}`;
                        grid_craft[i].style.backgroundImage = `url('${sprite_item}')`;
                        array_crafting[i] = itens_atalho[0];
                    }
                });
            else{
                grid_craft[i].style.backgroundImage = "";
                array_crafting[i] = null;
            }
        }
    }

    document.getElementById("array_craft").value = array_crafting;
}

function seta_item_criado(id_item){
    
    Object.keys(dados_itens).forEach(function(k){
        if(dados_itens[k]["id_item"] == id_item){
            sprite_produto = `../IMG/Itens/new/${dados_itens[k]["tipo_item"]}/${dados_itens[k]["nome_icon"]}`;
        
            document.getElementById("sprite_produto").innerHTML = "";
            document.getElementById("sprite_produto").innerHTML += `<img src='${sprite_produto}' style='width: 48px'>`;

            // document.getElementById("qtd_produto").innerHTML = typeof qtd !== "undefined" ? qtd : null;
        }
    });
}

if(document.getElementById("btn_fecha_tela_craft")){
    $("#btn_fecha_tela_craft").click(() => {
        $("#prancheta_criar_crafting").fadeOut();
        $("#btn_fecha_tela_craft").toggle();
    });
}