// Variavéis Globais 
var prancheta = false, alvo_anterior = null, inicio = 0, posicao_scroll = 0, libera_scroll = 1, cache_pesquisa = null, itens_genericos = 0, itens_ocultos = 0, tema = null, pesquisa = 0;

var qtd_itens = 0, qtd_itens_colet = 0, qtd_itens_renov = 0, qtd_itens_empil = 0, qtd_itens_colet_empil = 0, qtd_itens_colet_empil_n_renov = 0, qtd_itens_n_empil = 0;

var graphics = 0, total_load = 50, dados_globais;
var dados_itens = [], itens_atalho = [], array_crafting = [null, null, null, null, null, null, null, null, null];

function gerencia_scroll(valor) {
    libera_scroll = valor;
}

function scrollSincronizado(principal, sincronizado, versao_jogo) {

    let elem = document.getElementById(sincronizado);
    let doc = document.getElementById(principal);

    if (principal == "listagem") {
        if (libera_scroll) {
            if (versao_jogo > 2 || typeof versao_jogo == "undefined")
                value = parseInt(86 * doc.scrollTop / (doc.scrollHeight - doc.clientHeight));
            else
                value = parseFloat(90.7 * doc.scrollTop / (doc.scrollHeight - doc.clientHeight));

            elem.style.top = value + '%';
        }
    } else {
        if (versao_jogo > 2 || typeof versao_jogo == "undefined") {
            porcentagem = parseInt(86 * posicao_scroll / (elem.scrollHeight - elem.clientHeight));
            porcentagem = porcentagem - 59;
        } else {
            porcentagem = parseFloat(90.9 * posicao_scroll / (elem.scrollHeight - elem.clientHeight));
            porcentagem = porcentagem - 90;
        }

        value = parseInt((elem.scrollHeight / 180) * porcentagem);
        elem.scrollTop = value;
    }
}

function abre_menu(valor) {
    if (valor == 1) {
        $("#menu_completo").fadeOut();
        $("#prancheta_add").fadeToggle();
    } else if (valor == 2) {
        clique('prancheta', 1);
        $("#prancheta_add").fadeOut();
        $("#menu_completo").fadeToggle();
    } else {
        $("#menu_completo").fadeOut();
        $("#prancheta_add").fadeOut();
    }
}

function clique(valor, estado) {

    if (typeof valor == "string") {

        let mostra = document.getElementsByClassName("Prancheta");

        if (!prancheta && estado != 1) {
            document.getElementById("prancheta_add").style.display = "Block";
            mostra[0].style.display = "Block";
            prancheta = true;
        } else {
            document.getElementById("prancheta_add").style.display = "None";
            mostra[0].style.display = "None";
            prancheta = false;
        }
    } else {
        if (typeof event !== "undefined") {

            let tecla = event.keyCode;

            switch (tecla) {
                case 101: // e
                case 69: // E
                    alvo = "prancheta_add";

                    if (!menu)
                        alvo = "menu_completo";
                    break;
                case 119: // q
                case 87: // Q
                    alvo = "prancheta_add";
                    menu = !menu;
                    break;
            }
        }
    }
}

function filtragem(pesquisa, local) {

    if (local == 2) {
        local = 1, pesquisa = pesquisa.toString()

        document.getElementById("barra_pesquisa_input").value = pesquisa
    }

    if (pesquisa == null)
        pesquisa = 10

    if (pesquisa == 10) {
        pesquisa = 1

        let texto = ""

        if (document.getElementById("barra_pesquisa_input"))
            texto = document.getElementById("barra_pesquisa_input").value

        document.getElementById("titulo_aba").innerHTML = "Buscar"

        if (texto.length > 0)
            texto = texto.toLowerCase(), pesquisa = texto, local = 1
    }

    let categorias = ["Construcao", "Decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Pocoes", "Especiais", "Pesquisa"]
    let versoes = ["1.0", "1.1", "1.2", "1.3", "1.4", "1.5", "1.6", "1.7", "1.8", "1.9", "1.10", "1.11", "1.12", "1.13", "1.14", "1.15", "1.16", "1.17", "1.18", "1.19", "1.20"]

    let itens = 0, alvos = []

    // Descrição das abas do inventário para os itens
    if ((categorias[pesquisa]) && (categorias[pesquisa] !== "Pesquisa" && local !== 1))
        pesquisa = 0

    if (typeof pesquisa == "string" && pesquisa.length == 0)
        local = 0, pesquisa = 10

    if (pesquisa == "off" || pesquisa == "não_coletável" || pesquisa == "generico" || pesquisa == "oculto")
        verifica_posicao(pesquisa);
    else if (document.getElementById("img_configs_2")) {
        document.getElementById("img_configs_2").style.display = "none";
        document.getElementById("img_coletaveis_2").style.display = "none";
        document.getElementById("img_genericos_2").style.display = "none";
    }

    console.log(pesquisa, local)

    if (local == 0) { // Definindo a aba alvo
        alvos = document.getElementsByClassName(categorias[pesquisa]);

        if (document.getElementById("img_versoes_2")) {
            document.getElementById("img_versoes_2").style.display = "none";
            document.getElementById("lista_versoes").style.display = "none";
        }
    } else { // Listando os itens que possuem a class com nome semelhante ao pesquisado

        const itens = document.getElementsByClassName("slot_item")

        for (let i = 0; i < itens.length; i++) {
            if (itens[i].className.includes(pesquisa))
                alvos.push(itens[i])
        }
    }

    if ((versoes.includes(pesquisa) || categorias.includes(categorias[pesquisa])) && (itens_genericos || itens_ocultos)) { // Esconde todos os itens genéricos

        if (itens_genericos) mostrar_genericos()

        if (itens_ocultos) mostrar_ocultos()

        cache_pesquisa = null
        document.getElementById("barra_pesquisa_input").value = ""
    }

    // Limpa o cache de pesquisa
    if ((versoes.includes(pesquisa) || categorias.includes(categorias[pesquisa])) && cache_pesquisa != null)
        cache_pesquisa = null

    // Escondendo todos os itens de todas as categorias
    for (let i = 0; i < categorias.length; i++) {
        let esconde = document.getElementsByClassName(categorias[i])

        if (typeof pesquisa !== "string") {
            for (let x = 0; x < esconde.length; x++) {
                if (pesquisa != 10)
                    esconde[x].style.display = "None"
            }
        } else {
            for (let x = 0; x < esconde.length; x++) {
                if (typeof esconde[x] !== "undefined") {
                    esconde[x].style.display = "None"

                    if (typeof pesquisa == "string") {
                        // esconde[0].style.display = "Block";

                        if (i == 10) {
                            for (let i = 1; i < 5; i++)
                                esconde[i].style.display = "Block"

                            if (esconde.length > 6)
                                esconde[6].style.display = "Block"
                        }
                    }
                } else {
                    if (typeof esconde[x] !== "undefined") {
                        esconde[x].style.display = "Block"
                        itens++;
                    }
                }
            }

            ordena_guias_ativas(categorias, alvos)
        }
    }

    // Itens genéricos
    if (itens_genericos == 1)
        alvos = document.getElementsByClassName("Generico")

    console.log(alvos.length, alvos[0], alvos[1])

    // Exibindo os itens da categoria escolhida
    for (let i = 0; i < alvos.length; i++)
        alvos[i].style.display = "Block"

    preenche_slots_livres(qtd_itens)

    if (versoes.includes(pesquisa)) {
        $("#versao_referencia").fadeIn()
        document.getElementById("num_referencia").innerHTML = `${pesquisa} ( ${alvos.length} )`
    } else
        $("#versao_referencia").fadeOut()

    if (itens > 45 || alvos.length > 45) {
        document.getElementById("barra_scroll").style.display = "Block"
        document.getElementById("barra_scroll_block").style.display = "None"
    } else {
        document.getElementById("barra_scroll_block").style.display = "Block"
        document.getElementById("barra_scroll").style.display = "None"
    }

    nome_guia(pesquisa)
}

function nome_guia(alvo) {

    let categorias = ["Construcao", "Decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Pocoes", "Especiais", "Pesquisa"]

    // Definindo o nome da guia seleciona
    if (alvo == 0)
        nome_aba = "Blocos de construção"
    else if (alvo == 1)
        nome_aba = "Blocos decorativos"
    else if (alvo == 8)
        nome_aba = "Poções"
    else if (alvo == 9)
        nome_aba = "Itens especiais"
    else if (alvo == 10)
        nome_aba = "Buscar"
    else
        nome_aba = categorias[alvo]

    if (typeof alvo != "string")
        document.getElementById("titulo_aba").innerHTML = nome_aba
}

function preenche_slots_livres(qtd_itens) {

    let slots_livres = ((qtd_itens.length - 1) % 9)

    if (((qtd_itens.length - 1) % 9 != 0) || qtd_itens.length < 45) {
        if (qtd_itens.length < 45 && itens < 45)
            if (typeof alvo !== "string")
                slots_livres = 46 - qtd_itens.length
            else
                slots_livres = 45 - qtd_itens.length
        else
            slots_livres = 9 - (qtd_itens.length % 9)

        if (slots_livres == 9)
            slots_livres = 0
    }

    if (slots_livres > 0) {
        document.getElementById("complementa_slots").innerHTML = ""

        for (let j = 0; j < slots_livres; j++)
            document.getElementById("complementa_slots").innerHTML += "<div class='slot_item'></div>";
    } else // Limpa os slots de outras abas
        document.getElementById("complementa_slots").innerHTML = ""
}

function ordena_guias_ativas(categorias, alvos) {

    let guias = [];

    // Pesquisa sem inserção
    if (alvos.length < 1) {
        for (let i = 0; i < categorias.length; i++) {
            let alvos_mostra = document.getElementsByClassName(categorias[i]);

            if (alvos_mostra.length > 1) {
                for (let x = 0; x < alvos_mostra.length; x++)
                    alvos_mostra[x].style.display = "Block";

                if (!guias.includes(categorias[i])) guias.push(categorias[i]);
            }
        }
    } else {
        // Listando guias com itens ativos
        for (let i = 0; i < alvos.length; i++) {
            if (!guias.includes(alvos[i].classList[1]))
                guias.push(alvos[i].classList[1])
        }
    }

    // Desabilitando todas as guias
    for (let i = 0; i < categorias.length; i++) {
        let guia_alvo = document.getElementsByClassName(`aba_menu_${categorias[i]}`);

        if (guia_alvo.length > 0) guia_alvo[0].style.display = "None";
    }

    for (let i = 0; i < guias.length; i++) {
        let guia_alvo = document.getElementsByClassName(`aba_menu_${guias[i].toLowerCase()}`);
        if (guia_alvo.length > 0) guia_alvo[0].style.display = "Block";
    }
}

function mostrar_genericos() {
    let alvos = document.getElementsByClassName('Generico');

    if (itens_genericos == 0) {
        for (let i = 0; i < alvos.length; i++)
            alvos[i].style.display = "Block";

        itens_genericos = 1;
    } else {
        for (let i = 0; i < alvos.length; i++)
            alvos[i].style.display = "None";

        document.getElementById("img_genericos_2").style.display = "none";

        itens_genericos = 0;
    }
}

function mostrar_ocultos() {
    let alvos = document.getElementsByClassName('oculto');

    if (itens_ocultos == 0) {
        for (let i = 0; i < alvos.length; i++)
            alvos[i].style.display = "Block";

        itens_ocultos = 1;
    } else {
        for (let i = 0; i < alvos.length; i++)
            alvos[i].style.display = "None";

        document.getElementById("img_ocultos_2").style.display = "none";

        itens_ocultos = 0;
    }
}

function filtra_pesquisa() {
    let texto = (document.getElementById("barra_pesquisa_input").value).toLowerCase()

    filtragem(texto, 1)
}

function filtragem_automatica(alvo_filtragem, local) {

    if (local != null)
        document.getElementById("lista_versoes").style.display = "none";

    if (alvo_filtragem == "genéricos")
        alvo_filtragem = "generico";

    // Verifica se a requisição é igual a anterior e desabilita o elemento
    if (cache_pesquisa == "off" || cache_pesquisa == "não_coletável" || cache_pesquisa == "generico" || cache_pesquisa == "oculto") {
        if (cache_pesquisa != alvo_filtragem) {
            cache_pesquisa = alvo_filtragem;
            document.getElementById("barra_pesquisa_input").value = alvo_filtragem;
            verifica_posicao(alvo_filtragem);
        } else {
            document.getElementById("barra_pesquisa_input").value = "";
            cache_pesquisa = null;
        }
    } else {
        cache_pesquisa = alvo_filtragem;

        document.getElementById("barra_pesquisa_input").value = alvo_filtragem;

        verifica_posicao(alvo_filtragem);
    }

    if (alvo_filtragem == "generico" || (alvo_filtragem != "generico" && itens_genericos == 1))
        mostrar_genericos();

    if (alvo_filtragem == "oculto" || (alvo_filtragem != "oculto" && itens_ocultos == 1))
        mostrar_ocultos();

    filtra_pesquisa();
}

function lista_versoes() {
    $("#lista_versoes").toggle();
    $("#img_versoes_2").toggle();
}

dragElement(document.getElementById("barra_scroll"));

function dragElement(elmnt) {

    if (elmnt != null) {
        let pos2 = 0, pos4 = 0;

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

            if (pos4 > 185 && pos4 < 484) {
                elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
                posicao_scroll = pos4;
            }

            if (elmnt.offsetTop - pos2 < 0)
                elmnt.style.top = 0 + "px";

            if (elmnt.offsetTop - pos2 > 286)
                elmnt.style.top = 286 + "px";
        }

        function closeDragElement() {
            /* stop moving when mouse button is released:*/
            document.onmouseup = null;
            document.onmousemove = null;
        }
    }
}

function verifica_posicao(caso) {

    if (caso == "off")
        caso = 0;

    if (caso == "não_coletável")
        caso = 1;

    if (caso == "generico")
        caso = 2;

    if (caso == "oculto")
        caso = 3;

    alvos = ["img_configs", "img_coletaveis", "img_genericos", "img_ocultos"];
    alvos_finais = ["img_configs_2", "img_coletaveis_2", "img_genericos_2", "img_ocultos_2"];

    elemento = document.getElementById(alvos[caso]);
    posicao = elemento.getBoundingClientRect();

    for (let i = 0; i < alvos_finais.length; i++) {
        document.getElementById(alvos_finais[i]).style.display = "none";
    }

    if (caso != 3) {
        if (posicao.x > 622)
            document.getElementById(alvos_finais[caso]).style.animation = "none";
        else
            document.getElementById(alvos_finais[caso]).style.animation = "puxa_campos .5s";
    } else {
        if (posicao.y > 510)
            document.getElementById(alvos_finais[caso]).style.animation = "none";
        else
            document.getElementById(alvos_finais[caso]).style.animation = "puxa_campos2 .5s";
    }

    document.getElementById(alvos_finais[caso]).style.display = "block";
}

function exibe_detalhes_item(id_item) {
    window.location = `item_detalhes.php?id=${id_item}`;
}

function exibe_item(caminho_sprite) {
    localStorage.setItem("sprite_escolhido", caminho_sprite);
    window.location = `./sprite.html`;
}

function voltar_pag() {
    window.location = "index.php";
}

function previewImage(local) {
    let file = document.getElementById("input_img").files;

    // Criando o nome interno automaticamente
    nome_interno = file[0].name;
    nome_interno = nome_interno.replace('.png', "");

    if (local == 0)
        document.getElementById("barra_nome_interno_pr").value = nome_interno;
    else
        document.getElementById("barra_nome_interno").value = nome_interno;

    if (file.length > 0) {
        let fileReader = new FileReader();

        fileReader.onload = function (event) {
            document.getElementById("preview_sprite").setAttribute("src", event.target.result);
        };

        fileReader.readAsDataURL(file[0]);
    }
}

function apagarItem(id_item) {
    if (confirm("Deseja realmente remover?"))
        window.location = `../PHP/item_remover.php?id=${id_item}`;
}

function troca_itens(pag) {

    const esconde1 = document.getElementsByClassName("pag_1");
    esconde1[0].style.display = "None";

    const esconde2 = document.getElementsByClassName("pag_2");
    esconde2[0].style.display = "None";

    document.getElementById("pag_1").style.display = "None";
    document.getElementById("pag_2").style.display = "None";

    const alvo = document.getElementsByClassName("pag_" + pag);
    alvo[0].style.display = "Block";

    document.getElementById("pag_" + (3 - pag)).style.display = "Block";
}

function sincroniza_itens(receita, produto, qtd, local, id_item) {

    fetch('https://raw.githubusercontent.com/odnols/inventario-mine/main/Files/JSON/dados_locais.json')
        .then(response => response.json())
        .then(async res_artigo => {

            dados_itens = res_artigo;

            if (local !== 1)
                mostra_crafting(receita, produto, qtd, 1);
            else
                seleciona_item(id_item);
        });
}

function expande_sprite(caminho) {
    window.location.href = `./sprite.php?caminho=${caminho}`;
}

if (document.getElementById("btn_fecha_tela_craft")) {
    $("#btn_fecha_tela_craft").click(() => {
        $("#prancheta_criar_crafting").fadeOut();
        $("#btn_fecha_tela_craft").toggle();
    });
}