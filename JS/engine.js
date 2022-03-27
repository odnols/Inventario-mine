// Variavéis Globais 
var prancheta = false, alvo_anterior = null, inicio = 0, posicao_scroll = 0, libera_scroll = 1, cache_pesquisa = null, itens_genericos = 0, itens_ocultos = 0, tema = null, pesquisa = 0;

var qtd_itens = 0, qtd_itens_colet = 0, qtd_itens_renov = 0, qtd_itens_empil = 0, qtd_itens_colet_empil = 0, qtd_itens_colet_empil_n_renov = 0, qtd_itens_n_empil = 0;

var graphics = 0, total_load = 50, dados_globais;
var dados_itens = [];

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

        mostra = document.getElementsByClassName("Prancheta");

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
        if(typeof event != "undefined"){

            var tecla = event.keyCode;

            switch(tecla){
                case 101: // e
                case 69 : // E
                    if(!menu)
                        alvo = "menu_completo";
                    else
                        alvo = "prancheta_add";
                break;   
                case 119: // q
                case 87 : // Q
                    alvo = "prancheta_add";

                    if(menu)
                        menu = false;
                    else
                        menu = true;
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

        texto = document.getElementById("barra_pesquisa_input").value;
        document.getElementById("titulo_aba").innerHTML = "Buscar";        

        if(texto.length > 0){
            texto = texto.toLowerCase();
            alvo = texto;
            local = 1;
        }
    }

    categorias = ["Construcao", "Decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Pocoes", "Especiais", "Pesquisa"];
    versoes = ["1.0", "1.1", "1.2", "1.3", "1.4", "1.5", "1.6", "1.7", "1.8", "1.9", "1.10", "1.11", "1.12", "1.13", "1.14", "1.15", "1.16", "1.17", "1.18", "1.19"];

    itens = 0;

    if((categorias[alvo]) && (categorias[alvo] != "Pesquisa" && local != 1)) // Descrição das abas do inventário para os itens
        pesquisa = 0;

    if(typeof alvo == "string" && alvo.length == 0){
        local = 0;
        alvo = 10;
    }
    
    if(alvo == "off" || alvo == "não_coletável" || alvo == "generico" || alvo == "oculto")
        verifica_posicao(alvo);
    else{
        document.getElementById("img_configs_2").style.display = "none";
        document.getElementById("img_coletaveis_2").style.display = "none";
        document.getElementById("img_genericos_2").style.display = "none";
    }

    if(local == 0){ // Definindo a aba alvo
        alvos = document.getElementsByClassName(categorias[alvo]);

        document.getElementById("img_versoes_2").style.display = "none";
        document.getElementById("lista_versoes").style.display = "none";
    }else
        alvos = document.getElementsByClassName(alvo);
    
    if((versoes.includes(alvo) || categorias.includes(categorias[alvo])) &&  (itens_genericos || itens_ocultos)){ // Esconde todos os itens genéricos
        
        if(itens_genericos)
            mostrar_genericos();

        if(itens_ocultos)
            mostrar_ocultos();

        cache_pesquisa = null;
        document.getElementById("barra_pesquisa_input").value = "";
    }

    if((versoes.includes(alvo) || categorias.includes(categorias[alvo])) && cache_pesquisa != null) // Limpa o cache de pesquisa
        cache_pesquisa = null;
    
    // Escondendo todos os itens de todas as categorias
    for(var i = 0; i < categorias.length; i++){
        esconde = document.getElementsByClassName(categorias[i]);

        if(typeof alvo != "string"){
            for(var x = 0; x < esconde.length; x++){
                if(alvo != 10)
                    esconde[x].style.display = "None";
                else{
                    esconde[x].style.display = "Block";
                    itens++;
                }
            }
        }else{
            for(var x = 0; x < esconde.length; x++){
                if(typeof esconde[x] !== "undefined"){
                    esconde[x].style.display = "None";

                    if(typeof alvo == "string"){
                        esconde[0].style.display = "Block";

                        if(i == 10){
                            esconde[1].style.display = "Block";
                            esconde[2].style.display = "Block";
                            esconde[3].style.display = "Block";
                            esconde[4].style.display = "Block";
                            esconde[5].style.display = "Block";
                            esconde[6].style.display = "Block";
                        }
                    }
                }else{
                    if(typeof esconde[x] != "undefined"){
                        esconde[x].style.display = "Block";
                        itens++;
                    }
                }
            }
        }
    }

    // Itens genéricos
    if(itens_genericos == 1)
        alvos = document.getElementsByClassName("Generico");

    // Exibindo os itens da categoria escolhida
    for(var i = 0; i < alvos.length; i++){
        alvos[i].style.display = "Block";
    }

    var slots_livres;

    slots_livres = ((alvos.length - 1) % 9);

    if(((alvos.length - 1) % 9 != 0) || alvos.length < 45){
        if(alvos.length < 45 && itens < 45)
            if(typeof alvo != "string")
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
        
        for(var j = 0; j < slots_livres; j++){
            document.getElementById("complementa_slots").innerHTML += "<div class='slot_item'></div>";
        }
    }else // Limpa os slots de outras abas
        document.getElementById("complementa_slots").innerHTML = "";
    
    if(versoes.includes(alvo)){
        $("#versao_referencia").fadeIn();
        document.getElementById("num_referencia").innerHTML = alvo + " ( "+ alvos.length + " )";
    }else{
        $("#versao_referencia").fadeOut();
    }

    if(itens > 45 || alvos.length > 45){
        document.getElementById("barra_scroll").style.display = "Block";
        document.getElementById("barra_scroll_block").style.display = "None";
    }else{
        document.getElementById("barra_scroll_block").style.display = "Block";
        document.getElementById("barra_scroll").style.display = "None";
    }

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

function mostrar_genericos(){
    var alvos = document.getElementsByClassName('Generico');

    if(itens_genericos == 0){
        for(var i = 0; i < alvos.length; i++){
            alvos[i].style.display = "Block";
        }

        itens_genericos = 1;
    }else{
        for(var i = 0; i < alvos.length; i++){
            alvos[i].style.display = "None";
        }
        
        document.getElementById("img_genericos_2").style.display = "none";

        itens_genericos = 0;
    }
}

function mostrar_ocultos(){
    var alvos = document.getElementsByClassName('oculto');

    if(itens_ocultos == 0){
        for(var i = 0; i < alvos.length; i++){
            alvos[i].style.display = "Block";
        }

        itens_ocultos = 1;
    }else{
        for(var i = 0; i < alvos.length; i++){
            alvos[i].style.display = "None";
        }
        
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
        var pos2 = 0, pos4 = 0;
        if (document.getElementById(elmnt.id))
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
    
    for(var i = 0; i < alvos_finais.length; i++){
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
    window.location = "item_detalhes.php?id="+ id_item;
}

function voltar_pag(){
    window.location = "index.php";
}

function previewImage(local) {
    var file = document.getElementById("input_img").files;

    // Criando o nome interno automaticamente
    nome_interno = file[0].name;
    nome_interno = nome_interno.replace('.png', "");

    if(local == 0)
        document.getElementById("barra_nome_interno_pr").value = nome_interno;
    else
        document.getElementById("barra_nome_interno").value = nome_interno;

    if (file.length > 0) {
        var fileReader = new FileReader();

        fileReader.onload = function (event){
            document.getElementById("preview_sprite").setAttribute("src", event.target.result);
        };

        fileReader.readAsDataURL(file[0]);
    }
}

function apagarItem(id_item){
    if(confirm("Deseja realmente remover?"))
        window.location = "PHP/item_remover.php?id="+ id_item;
}

function importar_dados(){
    // $("#selecionar_celula_dados").toggle();
}

function troca_tema(versao_jogo){

    if(tema == null || tema == 1)
        localStorage.setItem('tema', "0");
    
    if(tema == 0)
        localStorage.setItem('tema', "1");
    
    sincroniza_tema(versao_jogo);
}

function sincroniza_tema(versao_jogo){

    tema = localStorage.getItem("tema");

    if(tema != null)
        tema = parseInt(tema);
    else
        tema = 1;

    alvo = "claro";

    if(tema == 0){
        alvo = "escuro";
        document.getElementById("icone_tema").innerHTML = "🌑";
        document.getElementById("bttn_troca_tema").style.backgroundColor = "rgb(39, 39, 45)";
        document.getElementById("filtro_colorido").style.background = " linear-gradient(0deg, rgba(0,0,0,0.9248074229691877) 0%, rgba(3, 26, 124, 0.67) 39%, rgba(0,212,255,0) 100%)";
    }

    if(alvo == "claro"){
        document.getElementById("icone_tema").innerHTML = "☀️";
        document.getElementById("bttn_troca_tema").style.backgroundColor = "white";
        document.getElementById("filtro_colorido").style.background = "linear-gradient(0deg, rgba(0,0,0,0.9248074229691877) 0%, rgba(9,121,99,0.6699054621848739) 39%, rgba(0,212,255,0) 100%)";
    }

    lista_templates = ["#prancheta_add", ".input_prancheta", "#barra_pesquisa_input", "#barra_scroll", ".slot_item", ".slot_item_add"];
    nome_template = ["prancheta.png", "barra_prancheta.png", "barra_pesquisa.png", "scroll.png", "slot.png", "slot_add.png"];

    lista_imagens = ["prancheta", "img_construcao", "img_decorativos", "img_redstone", "img_transportes", "img_diversos", "img_alimentos", "img_ferramentas", "img_combate", "img_pocoes", "img_especiais", "img_pesquisa", "barra_scroll_block", "menu", "img_ocultos"];
    nome_arquivos = ["prancheta.png", "aba_construcao.png", "aba_decorativos.png", "aba_redstone.png", "aba_transportes.png", "aba_diversos.png", "aba_alimentos.png", "aba_ferramentas.png", "aba_combate.png", "aba_pocoes.png", "aba_especiais.png", "aba_pesquisa.png", "scroll_bloqueado.png", "menu.png", "aba_oculto.png"];

    if(typeof versao_jogo != "undefined")
        if(versao_jogo <= 2){
            nome_arquivos[nome_arquivos.indexOf("menu.png")] = "menu_classic.png";
            
            document.getElementById("titulo_aba").innerHTML = "Seleção de item";

            document.getElementById("menu_completo").style.top = "0px";
            document.getElementById("menu_completo").style.left = "7%";
            document.getElementById("lista_itens").style.width = "440px";
            document.getElementById("listagem").style.top = "63px";
            document.getElementById("listagem").style.left = "9px";
            document.getElementById("listagem").style.height = "486px";
            document.getElementById("listagem").style.width = "433px";
            document.getElementById("lista_itens").style.height = "519px";
            document.getElementById("titulo_aba").style.top = "90%";
            document.getElementById("barra_rolagem").style.right = "111px";
            document.getElementById("barra_rolagem").style.height = "480px";
            document.getElementById("barra_rolagem").style.top = "69px";
        }
    else{
        botoes_menu = document.getElementsByClassName("botoes_menu");

        for(var i = 0; i < botoes_menu.length; i++){
            botoes_menu[i].style.display = "Block";
        }
    }
        
    for(var i = 0; i < lista_imagens.length; i++){ // Imagens
        imagem = document.getElementById(lista_imagens[i]);
        
        if(imagem != null)
            imagem.src = "IMG/Interface/"+ alvo +"/"+ nome_arquivos[i];
    }

    for(var i = 0; i < lista_templates.length; i++){
        if(lista_templates[i].includes("#")){ // Elementos que utilizam ID

            lista_templates[i] = lista_templates[i].replace("#", ""); 
            
            objeto = document.getElementById(lista_templates[i]);
            if(objeto != null)
                objeto.style.background = "url('IMG/Interface/"+ alvo +"/"+ nome_template[i] +"') no-repeat";
        }else{ // Elementos que estão referenciados como classes

            lista_templates[i] = lista_templates[i].replace(".", "");
            var alvos = document.getElementsByClassName(lista_templates[i]);

            for(var j = 0; j < alvos.length; j++){
                alvos[j].style.background = "url('IMG/Interface/"+ alvo +"/"+ nome_template[i] +"') no-repeat";
            }
        }
    }

    textos = document.getElementsByClassName("cor_textos");
    
    for(var i = 0; i < textos.length; i++){

        if(tema == 0){
            textos[i].style.color = "#ffffff";
            document.getElementById("titulo_aba").style.color = "#ffffff";
        }else{
            textos[i].style.color = "black";
            document.getElementById("titulo_aba").style.color = "#3f3f3f";
        }
    }
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

        if(typeof local != "undefined"){
            id_nome_item = "nome_item_minetp";
            id_descricao_item = "descricao_item_minetp";
            id_nome_interno = "nome_interno_minetp";
        }

        document.getElementById(id_nome_item).innerHTML = "";
        document.getElementById(id_descricao_item).innerHTML = "";

        let itens_especiais = ["", "item_encantado", "item_especial", "item_lendario"];
        let cores_efeitos = ["&1", "&2", "&3", "&4", "&r"];
        let nome_cores_efeitos = ["efeito_cor_azul", "efeito_cor_vermelha", "efeito_cor_roxa", "efeito_cor_verde", "efeito_cor_padrao"];

        if(cor_item > 0)
            document.getElementById(id_nome_item).innerHTML += "<span class='"+ itens_especiais[cor_item] +"'>"+ nome +"</span>";
        else
            document.getElementById(id_nome_item).innerHTML = nome;
        
        if(typeof descricao != "undefined"){
            if(descricao.indexOf("&") == -1){
                document.getElementById(id_descricao_item).style.color = "#a8a8a8";
                document.getElementById(id_descricao_item).style.textShadow = "3px 3px 0 #2a2a2a";

                if(typeof descricao != "undefined")
                    document.getElementById(id_descricao_item).innerHTML = descricao;
                else
                    document.getElementById(id_descricao_item).innerHTML = "";
            }else{

                alvos_replace = ["Construcao", "Decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Pocoes", "Especiais", "Generico"];

                if(pesquisa == 0){
                    for(var i = 0; i< alvos_replace.length; i++){
                        descricao = descricao.replace("[&1"+ alvos_replace[i], "");
                    }
                }else{
                    categorias_exib = ["Blocos de construção", "Blocos decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Poções", "Especiais", "Genérico"];

                    alvo_alteracao = descricao.split(" ");
                    alvo_alteracao[0] = alvo_alteracao[0].replace("[&1", "");

                    var i = alvos_replace.indexOf(alvo_alteracao[0]);
                    descricao = descricao.replace(alvo_alteracao[0], "[&1"+ categorias_exib[i]);
                }

                descricao_colorida = descricao.split("[");

                for(var j = 0; j < descricao_colorida.length; j++){
                    for(var i = 0; i < cores_efeitos.length; i++){
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

        if(typeof nome_interno != "undefined")
            document.getElementById(id_nome_interno).innerHTML = "minecraft:"+ nome_interno;
        else
            document.getElementById(id_nome_interno).innerHTML = "";

        if(typeof local == "undefined")
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
    }else{
        document.getElementById("minetip-tooltip").style.display = "None";
    }
}

function carregamento_secundario(res_artigo, ia){

    for(let h = ia; h < ia + 50; h++){
        
        if(typeof res_artigo[h] === "undefined" || res_artigo[h] === null) return;

        let valor = res_artigo[h];

        if(valor.tipo_item !== "Generico"){
            qtd_itens++
            
            if(valor.coletavel)
                qtd_itens_colet++

            if(valor.renovavel)
                qtd_itens_renov++;
            
            if(valor.empilhavel !== 0)
                qtd_itens_empil++;
            else
                qtd_itens_n_empil++;

            if(valor.coletavel && valor.empilhavel > 0){
                qtd_itens_colet_empil++;

                if(!valor.renovavel)
                    qtd_itens_colet_empil_n_renov++;
            }
        }

        let apelido = null;
        let converte = null;
        let descricao_pesq = null;
        let oculto_invt = valor.oculto_invt;
        let geracao = "new";
        let versao_add = `1.${valor.versao_add}`;
        let descricao = `[&1${valor.tipo_item}`;
        let empilhavel = null;
        let renovavel = "renovável";
        let coletavel = "não_coletável";

        descricao = `${descricao} ${valor.x}`;
        descricao_pes = descricao.replace("[&r", "");

        if(valor.programmer_art === 1 && graphics)
            geracao = "classic";

        if(valor.oculto_invt === 1)
            oculto_invt = "Oculto";

        if(!valor.renovavel)
            renovavel = "não_renovável";
        
        if(valor.empilhavel !== 0)
            empilhavel = "empilhável";

        if(valor.coletavel !== 0)
            coletavel = "coletável";

        for(let i = 0; i < valor.nome_item.length; i++){
            converte = `${converte} `;
            
            for(let x = 0; x <= i; x++){
                converte = `${converte}${valor.nome_item[x]}`;
            }
        }

        for(i = 0; i < descricao_pes.length; i++){
            descricao_pesq = `${descricao_pesq} `;
            
            for(x = 0; x <= i; x++){
                descricao_pesq = `${descricao_pesq}${descricao_pes[x]}`;
            }
        }

        let cor_item = 0;
        
        auto_completa = converte.toLocaleLowerCase();
        descricao_pesq = descricao_pesq.toLocaleLowerCase();

        let url_sprite = `https://raw.githubusercontent.com/odnols/inventario-mine/main/IMG/Itens/${geracao}/${valor.tipo_item}/${valor.nome_icon}`;

        for(let i = 0; i < 20; i++){ // Elimina todos os números de versão da descrição
            descricao_pesq = descricao_pesq.replace(`1.${i}`, "");
        }

        if(valor.tipo_item !== "Generico" && valor.oculto_invt !== "Oculto"){
            document.getElementById("grade_itens").innerHTML += `<div class='slot_item ${valor.tipo_item} 1.${valor.versao_add} ${renovavel} ${auto_completa} ${descricao_pesq} ${empilhavel} ${coletavel} ${descricao_pesq}' onmouseover='toolTip(\"${valor.nome_item}\", \"${descricao}\", \"${valor.nome_interno}\", ${cor_item})' onmouseout='toolTip()'><img class='icon_item' src='${url_sprite}'></div>`;
        }else{
            if(valor.oculto_invt !== "Oculto"){
                document.getElementById("grade_itens").innerHTML += `<div class='slot_item ${valor.tipo_item}' onmouseover='toolTip(\"${valor.nome_item}\", \"${descricao}\", \"${valor.nome_interno}\", ${cor_item})' onmouseout='toolTip()'><img class='icon_item' src='${url_sprite}'></div>`;
            }
        }
    }

    setTimeout(() => {

        document.getElementById("qtd_itens_inventario").innerHTML = qtd_itens;
        document.getElementById("qtd_itens_inventario_colet").innerHTML = qtd_itens_colet;
        document.getElementById("qtd_itens_inventario_renov").innerHTML = qtd_itens_renov;
        document.getElementById("qtd_itens_inventario_empil").innerHTML = qtd_itens_empil;
        document.getElementById("qtd_itens_inventario_colet_empil").innerHTML = qtd_itens_colet_empil;
        document.getElementById("qtd_itens_inventario_colet_empil_n_renov").innerHTML = qtd_itens_colet_empil_n_renov;
        document.getElementById("qtd_itens_inventario_n_empil").innerHTML = qtd_itens_n_empil;

        sincroniza_tema();
        categoria(0, 0);

        carregamento_secundario(res_artigo, ia + 50);
    }, 5000);
}

function sincroniza_itens(craft, produto, qtd){

    fetch('https://raw.githubusercontent.com/odnols/inventario-mine/main/JSON/dados_locais.json')
    .then(response => response.json())
    .then(async res_artigo => {
        
        dados_itens = res_artigo;
        mostra_crafting(craft, produto, qtd);
    });
}

function mostra_crafting(craft, produto, qtd){

    const grid = document.getElementsByClassName('grid_craft');
    let sprite_produto = "";

    if(dados_itens.length < 1){
        sincroniza_itens(craft, produto, qtd);
        return;
    }

    for(let i = 0; i < grid.length; i++){

        let sprite_item = "";

        if(craft[i] !== null || typeof craft[i] !== "undefined"){
            Object.keys(dados_itens).forEach(function(k){
                if(k == craft[i])
                    sprite_item = `IMG/Itens/new/${dados_itens[k]["tipo_item"]}/${dados_itens[k]["nome_icon"]}`;

                if(k == produto)
                    sprite_produto = `IMG/Itens/new/${dados_itens[k]["tipo_item"]}/${dados_itens[k]["nome_icon"]}`;
            });
            
            grid[i].style.backgroundImage = `url('${sprite_item}')`;
        }else
            grid[i].style.backgroundImage = `None`;
    }

    Object.keys(dados_itens).forEach(function(k){
        if(dados_itens[k]["id_item"] == produto){
            sprite_produto = `IMG/Itens/new/${dados_itens[k]["tipo_item"]}/${dados_itens[k]["nome_icon"]}`;
            nome_produto = dados_itens[k]["nome"];
        }
    });

    document.getElementById("sprite_produto").innerHTML = "";
    document.getElementById("sprite_produto").innerHTML += "<img src='"+ sprite_produto +"' style='width: 48px'>";

    document.getElementById("qtd_produto").innerHTML = typeof qtd !== "undefined" ? qtd : null;
}

function expande_sprite(caminho){
    window.location.href = "./sprite.php?caminho="+ caminho;
}