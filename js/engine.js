// Variavéis Globais 
var prancheta = false, posicao_scroll = 0, libera_scroll = 1, cache_pesquisa = null, itens_ocultos = 0, tema, pesquisa = 0

var qtd_itens = 0

var dados_itens = [], itens_atalho = [], array_crafting = [null, null, null, null, null, null, null, null, null]

const categorias_map = {
    0: "construcao",
    1: "decorativos",
    2: "redstone",
    3: "transportes",
    4: "diversos",
    5: "alimentos",
    6: "ferramentas",
    7: "combate",
    8: "pocoes",
    9: "especiais"
}

function gerencia_scroll(valor) {
    libera_scroll = valor
}

function get(alvo) {

    let alvos = document.getElementsByClassName(alvo)

    if (alvos.length < 1)
        alvos = document.getElementById(alvo)

    return alvos
}

function scrollSincronizado(principal, sincronizado, versao_jogo) {

    let elem = get(sincronizado)
    let doc = get(principal)

    if (principal == "listagem") {
        if (libera_scroll) {
            if (versao_jogo > 2 || typeof versao_jogo == "undefined")
                value = parseInt(86 * doc.scrollTop / (doc.scrollHeight - doc.clientHeight))
            else
                value = parseFloat(90.7 * doc.scrollTop / (doc.scrollHeight - doc.clientHeight))

            elem.style.top = `${value}%`
        }
    } else {
        if (versao_jogo > 2 || typeof versao_jogo == "undefined") {
            porcentagem = parseInt(86 * posicao_scroll / (elem.scrollHeight - elem.clientHeight))
            porcentagem = porcentagem - 59
        } else {
            porcentagem = parseFloat(90.9 * posicao_scroll / (elem.scrollHeight - elem.clientHeight))
            porcentagem = porcentagem - 90
        }

        value = parseInt((elem.scrollHeight / 180) * porcentagem)
        elem.scrollTop = value
    }
}

function abre_menu(valor) {
    if (valor == 1) {
        $("#menu_completo").fadeOut()
        $("#prancheta_add").fadeToggle()
    } else if (valor == 2) {
        clique('prancheta', 1)
        $("#prancheta_add").fadeOut()
        $("#menu_completo").fadeToggle()
    } else {
        $("#menu_completo").fadeOut()
        $("#prancheta_add").fadeOut()
    }
}

function clique(valor, estado) {

    if (typeof valor == "string") {

        let mostra = get("prancheta")

        if (!prancheta && estado != 1) {
            get("prancheta_add").style.display = "block"
            mostra[0].style.display = "block";
            prancheta = true
        } else {
            get("prancheta_add").style.display = "none"
            mostra[0].style.display = "none"
            prancheta = false
        }
    } else {
        if (typeof event !== "undefined") {

            let tecla = event.keyCode

            switch (tecla) {
                case 101: // e
                case 69: // E
                    alvo = "prancheta_add"

                    if (!menu)
                        alvo = "menu_completo"
                    break
                case 119: // q
                case 87: // Q
                    alvo = "prancheta_add"
                    menu = !menu
                    break
            }
        }
    }
}

function aba_menu(alvo) {

    if (get("img_versoes_2")) {
        get("img_versoes_2").style.display = "none"
        get("lista_versoes").style.display = "none"
    }

    // Filtrando os itens da aba escolhida
    filtragem(categorias_map[alvo], 0, true, alvo)
    nome_guia(alvo)
}

function filtragem(pesquisa_input, local, force_filter, alvo) {

    if (local == 2) {
        local = 1, pesquisa_input = pesquisa_input.toString()

        get("barra_pesquisa_input").value = pesquisa_input
    }

    if (pesquisa_input == null)
        pesquisa_input = 10

    if (pesquisa_input == 10) {
        pesquisa_input = 1

        let texto = ""

        if (get("barra_pesquisa_input"))
            texto = get("barra_pesquisa_input").value

        get("titulo_aba").innerHTML = "Buscar"

        if (texto.length > 0)
            texto = texto.toLowerCase(), pesquisa_input = texto, local = 1
    }

    let alvos = []
    let versoes = ["1.0", "1.1", "1.2", "1.3", "1.4", "1.5", "1.6", "1.7", "1.8", "1.9", "1.10", "1.11", "1.12", "1.13", "1.14", "1.15", "1.16", "1.17", "1.18", "1.19", "1.20", "1.21"]

    // Descrição das abas do inventário para os itens
    if (categorias_map[pesquisa_input] && (categorias_map[pesquisa_input] !== "pesquisa" && local !== 1))
        pesquisa_input = 0

    if (typeof pesquisa_input == "string" && pesquisa_input.length == 0)
        local = 0, pesquisa_input = 10

    if (pesquisa_input == "off" || pesquisa_input == "não_coletável" || pesquisa_input == "oculto")
        verifica_posicao(pesquisa_input)
    else if (get("img_configs_2")) {
        get("img_configs_2").style.display = "none"
        get("img_coletaveis_2").style.display = "none"
    }

    // Listando os itens que possuem a class com nome semelhante ao pesquisado
    let itens = get("slot_item")

    for (let i = 0; i < itens.length; i++)
        if (itens[i].className.includes(pesquisa_input) && !itens[i].className.includes("oculto"))
            alvos.push(itens[i])

    if ((versoes.includes(pesquisa_input) || categorias_map[pesquisa_input]) && itens_ocultos) {

        if (itens_ocultos) mostrar_ocultos()

        cache_pesquisa = null
        get("barra_pesquisa_input").value = ""
    }

    // Limpa o cache de pesquisa
    if ((versoes.includes(pesquisa_input) || categorias_map[pesquisa_input]) && cache_pesquisa)
        cache_pesquisa = null

    // Escondendo todos os itens de todas as categorias
    for (let i = 0; i < Object.keys(categorias_map).length; i++) {
        let esconde = get(categorias_map[i])

        if (typeof pesquisa_input !== "string") {
            for (let x = 0; x < esconde.length; x++)
                if (pesquisa_input != 10)
                    esconde[x].style.display = "none"
        } else {
            for (let x = 0; x < esconde.length; x++) {
                if (typeof esconde[x] !== "undefined") {
                    esconde[x].style.display = "none"

                    if (typeof pesquisa_input == "string") {

                        if (i == 10) {
                            for (let i = 1; i < 5; i++)
                                esconde[i].style.display = "block"

                            if (esconde.length > 6)
                                esconde[6].style.display = "block"
                        }
                    }
                } else
                    if (typeof esconde[x] !== "undefined") {
                        esconde[x].style.display = "block"
                        itens++
                    }
            }

            ordena_guias_ativas(alvos, force_filter, alvo)
        }
    }

    // Exibindo os itens da categoria escolhida
    for (let i = 0; i < alvos.length; i++)
        alvos[i].style.display = "block"

    preenche_slots_livres(qtd_itens)

    if (versoes.includes(pesquisa_input)) {
        $("#versao_referencia").fadeIn()
        get("num_referencia").innerHTML = `${pesquisa_input} ( ${alvos.length} )`
    } else
        $("#versao_referencia").fadeOut()

    if (itens > 45 || alvos.length > 45) {
        get("barra_scroll").style.display = "block"
        get("barra_scroll_block").style.display = "none"
    } else {
        get("barra_scroll_block").style.display = "block"
        get("barra_scroll").style.display = "none"
    }
}

function nome_guia(alvo) {

    // Definindo o nome da guia seleciona
    if (alvo == 0)
        nome_aba = "Blocos de construção"
    else if (alvo == 1)
        nome_aba = "Blocos decorativos"
    else if (alvo == 8)
        nome_aba = "Poções"
    else if (alvo == 9)
        nome_aba = "itens especiais"
    else if (alvo == 10)
        nome_aba = "Buscar"
    else
        nome_aba = `${categorias_map[alvo].slice(0, 1).toUpperCase()}${categorias_map[alvo].slice(1, categorias_map[alvo].length)}`

    if (nome_aba !== "Buscar")
        get("barra_pesquisa_input").style.display = "None"
    else
        get("barra_pesquisa_input").style.display = "Block"

    if (typeof alvo !== "string")
        get("titulo_aba").innerHTML = nome_aba

    if (nome_aba == "Buscar") {
        controla_menus(true)
        pesquisa = 1
    } else {
        controla_menus(false)
        pesquisa = 0
    }
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
        get("complementa_slots").innerHTML = ""

        for (let j = 0; j < slots_livres; j++)
            get("complementa_slots").innerHTML += "<div class='slot_item'></div>"
    } else // Limpa os slots de outras abas
        get("complementa_slots").innerHTML = ""
}

function ordena_guias_ativas(alvos, force_filter, alvo) {

    let guias = []

    // Pesquisa sem inserção
    if (alvos.length < 1) {
        for (let i = 0; i < Object.keys(categorias_map).length; i++) {
            let alvos_mostra = get(categorias_map[i])

            if (alvos_mostra.length > 1) {
                for (let x = 0; x < alvos_mostra.length; x++)
                    alvos_mostra[x].style.display = "block"

                if (!guias.includes(categorias_map[i])) guias.push(categorias_map[i])
            }
        }
    } else {
        if (!force_filter) { // Listando guias com itens ativos
            for (let i = 0; i < alvos.length; i++) {
                if (!guias.includes(alvos[i].classList[1]))
                    guias.push(alvos[i].classList[1])
            }
        } else
            if (typeof alvo !== "undefined")
                guias.push(categorias_map[alvo])
    }

    // Desabilitando todas as guias
    for (let i = 0; i < Object.keys(categorias_map).length; i++) {
        let guia_alvo = get(`aba_menu_${categorias_map[i].toLowerCase()}`)

        if (guia_alvo.length > 0) guia_alvo[0].style.display = "none"
    }

    for (let i = 0; i < guias.length; i++) {
        let guia_alvo = get(`aba_menu_${guias[i].toLowerCase()}`)
        if (guia_alvo.length > 0) guia_alvo[0].style.display = "block"
    }
}

function mostrar_ocultos() {
    let alvos = get('oculto')

    if (itens_ocultos == 0) {
        for (let i = 0; i < alvos.length; i++)
            alvos[i].style.display = "block"

        itens_ocultos = 1
    } else {
        for (let i = 0; i < alvos.length; i++)
            alvos[i].style.display = "none"

        get("img_ocultos_2").style.display = "none"

        itens_ocultos = 0
    }
}

function esconde_ocultos() {

    let alvos = get("oculto")

    for (let i = 0; i < alvos.length; i++)
        alvos[i].style.display = "none"

    get("img_ocultos_2").style.display = "none"
}

function filtra_pesquisa() {
    let texto = (get("barra_pesquisa_input").value).toLowerCase()

    filtragem(texto, 1, false)
}

function filtragem_automatica(alvo_filtragem, local) {

    if (local != null)
        get("lista_versoes").style.display = "none"

    // Verifica se a requisição é igual a anterior e desabilita o elemento
    if (cache_pesquisa == "off" || cache_pesquisa == "não_coletável" || cache_pesquisa == "oculto") {
        if (cache_pesquisa != alvo_filtragem) {
            cache_pesquisa = alvo_filtragem
            get("barra_pesquisa_input").value = alvo_filtragem
            verifica_posicao(alvo_filtragem)
        } else {
            get("barra_pesquisa_input").value = ""
            cache_pesquisa = null
        }
    } else {
        cache_pesquisa = alvo_filtragem

        get("barra_pesquisa_input").value = alvo_filtragem

        verifica_posicao(alvo_filtragem)
    }

    if (alvo_filtragem == "oculto" || (alvo_filtragem != "oculto" && itens_ocultos == 1))
        mostrar_ocultos()

    filtra_pesquisa()
}

function lista_versoes() {
    $("#lista_versoes").toggle()
    $("#img_versoes_2").toggle()
}

dragElement(get("barra_scroll"))

function dragElement(elmnt) {

    if (elmnt != null) {
        let pos2 = 0, pos4 = 0

        if (get(elmnt.id))
            get(elmnt.id).onmousedown = dragMouseDown
        else
            elmnt.onmousedown = dragMouseDown

        function dragMouseDown(e) {
            e = e || window.event
            e.preventDefault()
            // get the mouse cursor position at startup:
            pos4 = e.clientY
            document.onmouseup = closeDragElement
            // call a function whenever the cursor moves:
            document.onmousemove = elementDrag
        }

        function elementDrag(e) {
            e = e || window.event
            e.preventDefault()
            // calculate the new cursor position:
            pos2 = pos4 - e.clientY
            pos4 = e.clientY

            // set the element's new position:
            scrollSincronizado('barra_rolagem', 'listagem')

            if (pos4 > 185 && pos4 < 484) {
                elmnt.style.top = `${(elmnt.offsetTop - pos2)}px`
                posicao_scroll = pos4
            }

            if (elmnt.offsetTop - pos2 < 0)
                elmnt.style.top = "0px"

            if (elmnt.offsetTop - pos2 > 286)
                elmnt.style.top = "286px"
        }

        function closeDragElement() {
            /* stop moving when mouse button is released:*/
            document.onmouseup = null
            document.onmousemove = null
        }
    }
}

function verifica_posicao(caso) {

    if (caso == "off")
        caso = 0

    if (caso == "não_coletável")
        caso = 1

    if (caso == "oculto")
        caso = 2

    alvos = ["img_configs", "img_coletaveis", "img_ocultos"]
    alvos_finais = ["img_configs_2", "img_coletaveis_2", "img_ocultos_2"]

    elemento = get(alvos[caso])
    posicao = elemento.getBoundingClientRect()

    for (let i = 0; i < alvos_finais.length; i++)
        get(alvos_finais[i]).style.display = "none"

    if (caso != 3) {
        if (posicao.x > 622)
            get(alvos_finais[caso]).style.animation = "none"
        else
            get(alvos_finais[caso]).style.animation = "puxa_campos .5s"
    } else {
        if (posicao.y > 510)
            get(alvos_finais[caso]).style.animation = "none"
        else
            get(alvos_finais[caso]).style.animation = "puxa_campos2 .5s"
    }

    get(alvos_finais[caso]).style.display = "block"
}

function exibe_detalhes_item(id_item) {
    window.location = `item_detalhes.php?id=${id_item}`
}

function exibe_item(caminho_sprite) {
    localStorage.setItem("sprite_escolhido", caminho_sprite)
    window.location = `./sprite.html`
}

function voltar_pag() {
    window.location = "index.php"
}

function previewImage(local) {
    let file = get("input_img").files

    // Criando o nome interno automaticamente
    nome_interno = file[0].name
    nome_interno = nome_interno.replace('.png', "")

    if (local == 0)
        get("barra_nome_interno_pr").value = nome_interno
    else
        get("barra_nome_interno").value = nome_interno

    if (file.length > 0) {
        let fileReader = new FileReader()

        fileReader.onload = function (event) {
            get("preview_sprite").setAttribute("src", event.target.result)
        }

        fileReader.readAsDataURL(file[0])
    }
}

function apagarItem(id_item) {
    if (confirm("Deseja realmente remover?"))
        window.location = `../php/item_remover.php?id=${id_item}`
}

function troca_itens(pag) {

    const esconde1 = get("pag_1_opcoes")
    esconde1[0].style.display = "none"

    const esconde2 = get("pag_2_opcoes")
    esconde2[0].style.display = "none"

    get("pag_1").style.display = "none"
    get("pag_2").style.display = "none"

    get("pag_1_opcoes")[0].style.display = "none"
    get("pag_2_opcoes")[0].style.display = "none"

    const alvo = get(`pag_${pag}_opcoes`)
    alvo[0].style.display = "block"

    get("pag_" + (3 - pag)).style.display = "block"
}

function sincroniza_itens(receita, produto, qtd, local, id_item) {

    fetch('https://raw.githubusercontent.com/odnols/inventario-mine/main/files/JSON/dados_locais.json')
        .then(response => response.json())
        .then(async res_artigo => {

            dados_itens = res_artigo

            if (local !== 1)
                mostra_crafting(receita, produto, qtd, 1)
            else
                seleciona_item(id_item)
        })
}

function expande_sprite(caminho) {
    window.location.href = `./sprite.php?caminho=${caminho}`
}

if (get("btn_fecha_tela_craft")) {
    $("#btn_fecha_tela_craft").click(() => {
        $("#prancheta_criar_crafting").fadeOut()
        $("#btn_fecha_tela_craft").toggle()
    })
}

function escolhe_aba_menu(id_item) {
    window.location = `../modules/aba_menu.php?id=${id_item}`
}

function controla_menus(caso) {

    let alvos = get("opcoes_laterais")
    let status = "None"

    // Mostrando ou escondendo o icone da pesquisa clicada
    let icon_busca = get("aba_menu_pesquisa")
    icon_busca[0].style.display = status

    if (caso)
        status = "Block"

    // Mostrando ou escondendo as guias laterais
    for (let i = 0; i < alvos.length; i++)
        alvos[i].style.display = status
}