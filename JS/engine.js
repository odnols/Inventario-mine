// Variavéis Globais 
var prancheta = false, alvo_anterior = null, inicio = 0, posicao_scroll = 0, libera_scroll = 1, cache_pesquisa = null, itens_genericos = 0;

function gerencia_scroll(valor){
    libera_scroll = valor;
}

function scrollSincronizado(principal, sincronizado){

    let elem = document.getElementById(sincronizado);
    let doc = document.getElementById(principal);

    if(principal == "listagem"){
        if(libera_scroll){
            value = parseInt(86 * doc.scrollTop / (doc.scrollHeight - doc.clientHeight));

            elem.style.top = value + '%';
        }
    }else{
        porcentagem = parseInt(86 * posicao_scroll / (elem.scrollHeight - elem.clientHeight));
        porcentagem = porcentagem - 59;

        value = parseInt((elem.scrollHeight / 180) * porcentagem);
        elem.scrollTop = value;        
    }
}

function main(){
    document.addEventListener("keypress", clique);
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

        if(alvo == 1)
            alvo = "1.0";
        else if(alvo == 1.101)
            alvo = "1.10";
        else
            alvo = alvo.toString();
        
        document.getElementById("barra_pesquisa_input").value = alvo;
    }

    if(alvo == 10){
        texto = document.getElementById("barra_pesquisa_input").value;
        document.getElementById("titulo_aba").innerHTML = "Buscar";        

        if(texto.length > 0){
            texto = texto.toLowerCase();
            alvo = texto;
            local = 1;
        }
    }

    categorias = ["Construcao", "Decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Pocoes", "Especiais", "Pesquisa"];
    versoes = ["1.0", "1.1", "1.2", "1.3", "1.4", "1.5", "1.6", "1.7", "1.8", "1.9", "1.10", "1.11", "1.12", "1.13", "1.14", "1.15", "1.16", "1.17", "1.18"];

    itens = 0;
    
    if(typeof alvo == "string" && alvo.length == 0){
        local = 0;
        alvo = 10;
    }
    
    if(alvo == "off" || alvo == "não_coletável" || alvo == "Generico")
        verifica_posicao(alvo);
    else{
        document.getElementById("img_configs_2").style.display = "none";
        document.getElementById("img_coletaveis_2").style.display = "none";
        // document.getElementById("img_genericos_2").style.display = "none";
    }

    if(local == 0){
        // Definindo a aba alvo
        alvos = document.getElementsByClassName(categorias[alvo]);

        document.getElementById("img_versoes_2").style.display = "none";
        document.getElementById("lista_versoes").style.display = "none";
    }else
        alvos = document.getElementsByClassName(alvo);
    
    
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
                            // esconde[5].style.display = "Block";
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

    // Exibindo os itens da categoria escolhida
    for(var i = 0; i < alvos.length; i++){
        alvos[i].style.display = "Block";
        itens++;
    }

    var slots_livres = itens;

    if(alvo == 10)
        slots_livres -= 20;

    if(((alvos.length - 1) % 9) != 0 || alvos.length == 1){
        if(alvo != 10 && typeof alvo != "string")
            slots_livres = alvos.length - 1;

        if(alvos.length - 1 < 45 && alvo !== 10 && typeof alvo != "string")
            slots_livres = 47 - alvos.length - 1;
        else if(alvo !== 10 && typeof alvo != "string")
            slots_livres = ((slots_livres % 9) - 9) * - 1;

        if(alvo === 10 || typeof alvo === "string"){
            if(itens < 45)
                slots_livres = 45 - itens;
            else
                slots_livres = ((slots_livres % 9) - 9) * - 1;
        }

        document.getElementById("complementa_slots").innerHTML = "";

        for(var j = 0; j < slots_livres; j++){
            document.getElementById("complementa_slots").innerHTML += "<div id='slot_item'></div>";
        }
    }else // Limpa os slots de outras abas
        document.getElementById("complementa_slots").innerHTML = "";

    if(versoes.includes(alvo)){
        $("#versao_referencia").fadeIn();
        document.getElementById("num_referencia").innerHTML = itens;
    }else{
        $("#versao_referencia").fadeOut();
    }

    if(itens >= 47){
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
        nome_aba = "Blocos especiais";
    else if(alvo == 10)
        nome_aba = "Buscar";
    else
        nome_aba = categorias[alvo];

    if(typeof alvo != "string")
        document.getElementById("titulo_aba").innerHTML = nome_aba;
}

function mostrar_todos(alvo){
    var alvos = document.getElementsByClassName(alvo);


    if(itens_genericos == 0){
        for(var i = 0; i < alvos.length; i++){
            alvos[0].style.display = "Block";
        }   

        itens_genericos = 1;
    }else{
        for(var i = 0; i < alvos.length; i++){
            alvos[0].style.display = "None";
        }
        
        itens_genericos = 0;
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
    
    // Verifica se a requisição é igual a anterior e desabilita o elemento
    if(cache_pesquisa == "off" || cache_pesquisa == "não_coletável" || cache_pesquisa == "Generico"){
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

    if(alvo_filtragem == "Generico")
        mostrar_todos("Generico");

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

    if(caso == "Generico")
        caso = 2;

    alvos = ["img_configs", "img_coletaveis"];
    alvos_finais = ["img_configs_2", "img_coletaveis_2"];

    elemento = document.getElementById(alvos[caso]);
    posicao = elemento.getBoundingClientRect();
    
    for(var i = 0; i < alvos_finais.length; i++){
        document.getElementById(alvos_finais[i]).style.display = "none";
    }

    if(posicao.x > 622)
        document.getElementById(alvos_finais[caso]).style.animation = "none";
    else
        document.getElementById(alvos_finais[caso]).style.animation = "puxa_campos .5s";

    document.getElementById(alvos_finais[caso]).style.display = "block";
}

function exibe_detalhes_item(id_item){
    window.location = "item_detalhes.php?id="+ id_item;
}

function voltar_pag(){
    window.location = "index.php";
}

function previewImage() {
    var file = document.getElementById("input_img").files;

    // Criando o nome interno automaticamente
    nome_interno = file[0].name;
    nome_interno = nome_interno.replace('.png', "");

    document.getElementById("barra_nome_interno").value = nome_interno;

    if (file.length > 0) {
        var fileReader = new FileReader();

        fileReader.onload = function (event) {
            document.getElementById("preview").setAttribute("src", event.target.result);
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