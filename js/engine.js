// Variavéis Globais 
var prancheta = false, cache_pesquisa = null, itens_ocultos = 0, tema, pesquisa = 0

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

function get(alvo) {

    let alvos = document.getElementsByClassName(alvo)

    if (alvos.length < 1)
        alvos = document.getElementById(alvo)

    return alvos
}

function scrollSincronizado(principal, sincronizado, versao_jogo) {
    const listagem = document.getElementById(principal === "listagem" ? principal : sincronizado)
    const puxador = document.getElementById(principal === "listagem" ? sincronizado : "barra_scroll")
    const trilho = puxador && puxador.parentElement

    if (!listagem || !puxador || !trilho)
        return

    const maximoScroll = listagem.scrollHeight - listagem.clientHeight
    const maximoPuxador = trilho.clientHeight - puxador.offsetHeight

    if (maximoScroll <= 0 || maximoPuxador <= 0) {
        puxador.style.top = "0px"
        if (maximoScroll <= 0)
            listagem.scrollTop = 0
        return
    }

    if (principal === "listagem") {
        puxador.style.top = `${(listagem.scrollTop / maximoScroll) * maximoPuxador}px`
    } else {
        const posicaoPuxador = Math.max(0, Math.min(puxador.offsetTop, maximoPuxador))
        const scrollProposto = (posicaoPuxador / maximoPuxador) * maximoScroll
        const passoSlot = 54
        const scrollPorSlots = Math.round(scrollProposto / passoSlot) * passoSlot
        listagem.scrollTop = Math.min(scrollPorSlots, maximoScroll)
        puxador.style.top = `${(listagem.scrollTop / maximoScroll) * maximoPuxador}px`
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

// As máscaras das abas laterais (off, não coletáveis, ocultos) só aparecem quando o termo correspondente está digitado na barra
function sincroniza_mascaras_pesquisa() {
    const barra = get("barra_pesquisa_input")
    const termo = barra ? barra.value.trim().toLowerCase() : ""
    const abas = { img_configs: "off", img_coletaveis: "não_coletável", img_ocultos: "oculto" }

    for (const id in abas) {
        const mascara = get(id + "_2")
        const ativa = termo === abas[id]

        if (mascara && !ativa) mascara.style.display = "none"
    }
}

function aba_menu(alvo) {

    if (get("img_versoes_2")) {
        get("img_versoes_2").style.display = "none"
        get("lista_versoes").style.display = "none"
    }

    // Filtrando os itens da aba escolhida
    filtragem(categorias_map[alvo], 0, true, alvo)
    const listagem = document.getElementById("listagem")
    const puxador = document.getElementById("barra_scroll")
    if (listagem)
        listagem.scrollTop = 0
    if (puxador)
        puxador.style.top = "0px"

    nome_guia(alvo)
}

function filtragem(pesquisa_input, local, force_filter, alvo) {

    if (local == 2) {
        local = 1, pesquisa_input = pesquisa_input.toString()

        get("barra_pesquisa_input").value = pesquisa_input
    }

    if (!pesquisa_input)
        pesquisa_input = 10

    if (pesquisa_input == 10) {
        pesquisa_input = 1

        let texto = ""

        if (get("barra_pesquisa_input"))
            texto = get("barra_pesquisa_input").value

        get("titulo_aba").innerHTML = "Buscar"

        if (texto.length > 0)
            texto = texto.toLowerCase(), pesquisa_input = texto, local = 1
        else
            pesquisa_input = "slot_item" // Todos os slots possuem essa classe, então nenhum item é filtrado
    }

    let alvos = []
    let versoes = ["1.0", "1.1", "1.2", "1.3", "1.4", "1.5", "1.6", "1.7", "1.8", "1.9", "1.10", "1.11", "1.12", "1.13", "1.14", "1.15", "1.16", "1.17", "1.18", "1.19", "1.20", "1.21", "26.1", "26.2", "26.3", "26.4"]

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
    let itens = document.getElementsByClassName("slot_item")

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
    for (let i = 0; i < itens.length; i++)
        itens[i].style.display = "None"

    ordena_guias_ativas(alvos, force_filter, alvo, pesquisa_input)
    preenche_slots_livres()
    sincroniza_mascaras_pesquisa()

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
        get("barra_scroll").style.top = "0px"
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
        nome_aba = "Blocos de operador"
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
        get("barra_pesquisa_input").value = null
    }
}

function preenche_slots_livres() {
    const complemento = document.getElementById("complementa_slots")
    const listagem = document.getElementById("listagem")

    if (!complemento || !listagem)
        return

    // Colunas e linhas visíveis dependem do tamanho da lista (8 colunas nas versões 1.0 a 1.2)
    const colunas = Math.max(1, Math.floor(listagem.clientWidth / 54))
    const linhasVisiveis = Math.max(1, Math.floor(listagem.clientHeight / 54))
    const slotsPrimeiraTela = colunas * linhasVisiveis

    const quantidadeItens = Array.from(listagem.querySelectorAll(".slot_item"))
        .filter(item => !item.closest("#complementa_slots") && item.style.display !== "none")
        .length

    const slotsLivres = quantidadeItens < slotsPrimeiraTela
        ? slotsPrimeiraTela - quantidadeItens
        : (colunas - quantidadeItens % colunas) % colunas

    complemento.replaceChildren()

    for (let indice = 0; indice < slotsLivres; indice++)
        complemento.appendChild(document.createElement("div")).className = "slot_item"
}

function ordena_guias_ativas(alvos, force_filter, alvo, pesquisa_input) {

    let guias = []
    let versao_jogo
    qtd_itens = 0

    if (get("input_versao_jogo"))
        versao_jogo = get("input_versao_jogo").value

    // Pesquisa sem inserção
    if (alvo === 10 || typeof alvo === "undefined") {
        for (let i = 0; i < Object.keys(categorias_map).length; i++)
            if (!guias.includes(categorias_map[i])) {
                exibe_itens_categoria(categorias_map[i], versao_jogo, pesquisa_input)
                guias.push(categorias_map[i])
            }
    } else {

        exibe_itens_categoria(categorias_map[alvo], versao_jogo)
        guias.push(categorias_map[alvo])

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
        const guia_alvo = document.getElementsByClassName(`aba_menu_${categorias_map[i].toLowerCase()}`)

        if (guia_alvo.length > 0) guia_alvo[0].style.display = "none"
    }

    for (let i = 0; i < guias.length; i++) {
        const guia_alvo = document.getElementsByClassName(`aba_menu_${guias[i].toLowerCase()}`)
        if (guia_alvo.length > 0) guia_alvo[0].style.display = "block"
    }
}

function ordinal_versao(versao) {
    const partes = String(versao).split(".")
    const principal = parseInt(partes[0]), secundaria = parseInt(partes[1])

    if (isNaN(principal) || isNaN(secundaria))
        return NaN

    // As versões 26.x vêm logo após a 1.21
    return principal >= 26 ? 21 + secundaria : secundaria
}

function exibe_itens_categoria(alvo, versao_jogo, pesquisa_input) {

    const alvos_mostra = document.getElementsByClassName(alvo)
    for (let x = 0; x < alvos_mostra.length; x++) {

        if (versao_jogo && alvos_mostra[x].className.includes("slot_item")) { // Utilizado para versionamento na página de histórico
            const versao_item = Array.from(alvos_mostra[x].classList).find(classe => /^\d+\.\d+$/.test(classe))

            if (ordinal_versao(versao_item) <= parseInt(versao_jogo) && alvos_mostra[x].className.includes(alvo)) {
                alvos_mostra[x].style.display = "block"
                qtd_itens++
            }
        } else if (pesquisa_input) {
            if (alvos_mostra[x].className.includes(pesquisa_input)) {
                alvos_mostra[x].style.display = "block"
                qtd_itens++
            }
        } else {
            alvos_mostra[x].style.display = "block"
            qtd_itens++
        }
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

function inicia_arraste_scroll() {
    const puxador = document.getElementById("barra_scroll")
    const lista = document.getElementById("listagem")

    dragElement(puxador)

    if (puxador && puxador.parentElement && !puxador.parentElement.dataset.clickScrollInicializado) {
        puxador.parentElement.dataset.clickScrollInicializado = "true"
        puxador.parentElement.addEventListener("click", event => {
            if (event.target === puxador || puxador.contains(event.target))
                return

            const trilho = puxador.parentElement
            const limitesTrilho = trilho.getBoundingClientRect()
            const maximoPuxador = trilho.clientHeight - puxador.offsetHeight
            const posicaoPuxador = Math.max(
                0,
                Math.min(event.clientY - limitesTrilho.top - puxador.offsetHeight / 2, maximoPuxador)
            )

            puxador.style.top = `${posicaoPuxador}px`
            scrollSincronizado(trilho.id, "listagem")
        })
    }

    if (lista && !lista.dataset.wheelScrollInicializado) {
        lista.dataset.wheelScrollInicializado = "true"

        const rolaPorSlot = event => {
            if (event.deltaY === 0)
                return

            event.preventDefault()
            const passoSlot = 54
            const maximoScroll = lista.scrollHeight - lista.clientHeight
            const indiceSlot = Math.round(lista.scrollTop / passoSlot)
            const proximoScroll = (indiceSlot + Math.sign(event.deltaY)) * passoSlot
            lista.scrollTop = Math.max(0, Math.min(proximoScroll, maximoScroll))
        }

        lista.addEventListener("wheel", rolaPorSlot, { passive: false })

        // A barra de scroll fica fora da lista, então também precisa responder à roda
        const trilho = puxador && puxador.parentElement
        if (trilho)
            trilho.addEventListener("wheel", rolaPorSlot, { passive: false })
    }
}

if (document.readyState === "loading")
    document.addEventListener("DOMContentLoaded", inicia_arraste_scroll, { once: true })
else
    inicia_arraste_scroll()

function dragElement(elmnt) {

    if (elmnt != null) {
        const trilho = elmnt.parentElement
        let distanciaPuxador = 0

        function dragMouseDown(e) {
            e.preventDefault()
            const limitesPuxador = elmnt.getBoundingClientRect()
            distanciaPuxador = e.clientY - limitesPuxador.top
            document.onmouseup = closeDragElement
            document.onmousemove = elementDrag
        }

        function elementDrag(e) {
            e.preventDefault()
            const maximoPuxador = trilho.clientHeight - elmnt.offsetHeight
            const limitesTrilho = trilho.getBoundingClientRect()
            const posicaoPuxador = Math.max(
                0,
                Math.min(e.clientY - limitesTrilho.top - distanciaPuxador, maximoPuxador)
            )

            elmnt.style.top = `${posicaoPuxador}px`
            scrollSincronizado(trilho.id, "listagem")
        }

        function closeDragElement() {
            document.onmouseup = null
            document.onmousemove = null
        }

        elmnt.onmousedown = dragMouseDown
    }
}

function verifica_posicao(caso) {
    sincroniza_mascaras_pesquisa()

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

            const idsItensJson = new Set(res_artigo.map(item => String(item.id)))
            const itensBanco = Array.from(document.querySelectorAll("[data-craft-item-id]"), elemento => ({
                id: Number(elemento.dataset.craftItemId),
                type: elemento.dataset.craftItemType,
                icon: elemento.dataset.craftItemIcon,
                name: elemento.dataset.craftItemName,
                internal_name: elemento.dataset.craftItemInternalName
            }))

            dados_itens = res_artigo.concat(
                itensBanco.filter(item => !idsItensJson.has(String(item.id)))
            )

            if (document.getElementById("craft_prancheta") && local !== 1)
                seleciona_tipo_craft(document.getElementById("tipo_craft").value, true)
            else if (local !== 1)
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

function escolhe_aba_menu() {
    const seletor = document.getElementById("seletor_guia_menu")
    if (!seletor)
        return

    seletor.hidden = !seletor.hidden
    if (!seletor.hidden)
        seletor.focus()
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

    // As máscaras também têm a classe opcoes_laterais e não podem ser reexibidas aqui
    sincroniza_mascaras_pesquisa()
}
function exportar_dados(evento) {
    evento.preventDefault();
    const botao = document.getElementById("button_exportar_dados");
    if (botao.dataset.exportando) return;

    const texto_original = botao.textContent;
    botao.dataset.exportando = "1";
    botao.textContent = "Exportando...";

    fetch("../php/exportar_dados.php?ajax=1")
        .then(resposta => resposta.json())
        .then(resultado => {
            botao.textContent = resultado.ok ? "Exportado (" + resultado.itens + ")" : "Falha ao exportar";
        })
        .catch(() => { botao.textContent = "Falha ao exportar"; })
        .finally(() => {
            setTimeout(() => {
                botao.textContent = texto_original;
                delete botao.dataset.exportando;
            }, 2000);
        });
}

// Atualiza estatísticas e botões do index sem recarregar a página
async function atualiza_painel_index() {
    // Evita que as animações de entrada das guias laterais reiniciem ao refazer o filtro
    document.querySelectorAll(".opcoes_laterais, .opcoes_baixo, #img_configs_2, #img_coletaveis_2, #img_ocultos_2, #img_versoes_2")
        .forEach(elemento => { elemento.style.animation = "none"; });

    const resposta = await fetch(window.location.href, { cache: "no-store" });
    const novo = new DOMParser().parseFromString(await resposta.text(), "text/html");

    ["text_estatsc", "botoes_ferramentas"].forEach(id => {
        const atual = document.getElementById(id);
        const atualizado = novo.getElementById(id);
        if (atual && atualizado) atual.innerHTML = atualizado.innerHTML;
    });

    const listagem_atual = document.getElementById("listagem");
    const listagem_nova = novo.getElementById("listagem");
    if (listagem_atual && listagem_nova) {
        listagem_atual.innerHTML = listagem_nova.innerHTML;

        const busca = document.getElementById("barra_pesquisa_input");
        if (busca) busca.value = "";
        itens_ocultos = 0;
        cache_pesquisa = null;
        try { aba_menu(10); } finally { sincroniza_tema(undefined, 1); }
    } else sincroniza_tema(undefined, 1);
}

function executa_acao_segundo_plano(evento, botao_id, url, texto_andamento, texto_final) {
    evento.preventDefault();
    const botao = document.getElementById(botao_id);
    if (botao.dataset.executando) return;
    botao.dataset.executando = "1";
    botao.textContent = texto_andamento;

    fetch(url)
        .then(resposta => resposta.json())
        .then(resultado => {
            if (!resultado.ok) throw new Error("falha");
            return atualiza_painel_index();
        })
        .catch(() => {
            botao.textContent = "Falha na operação";
            setTimeout(() => atualiza_painel_index().catch(() => { }), 2000);
        });
}

function importar_dados(evento) {
    executa_acao_segundo_plano(evento, "button_importar_dados", "../php/importar_dados.php?ajax=1", "Importando...");
}

function limpar_dados(evento) {
    if (!confirm("Apagar todos os dados salvos no banco?")) return evento.preventDefault();
    executa_acao_segundo_plano(evento, "button_apagar_dados", "../php/limpar_dados.php?ajax=1", "Limpando...");
}

function atualizar_html(evento) {
    evento.preventDefault();
    const botao = document.getElementById("button_atualizar_html");
    if (botao.dataset.executando) return;

    const texto_original = botao.textContent;
    botao.dataset.executando = "1";
    botao.textContent = "Atualizando...";

    fetch("../modules/criar_pagina.php?ajax=1")
        .then(resposta => resposta.json())
        .then(resultado => { botao.textContent = resultado.ok ? "HTML atualizado" : "Falha ao atualizar"; })
        .catch(() => { botao.textContent = "Falha ao atualizar"; })
        .finally(() => {
            setTimeout(() => {
                botao.textContent = texto_original;
                delete botao.dataset.executando;
            }, 2000);
        });
}

// Alterna entre os sprites atuais e os originais (Programmer Art) sem recarregar a página
function alterna_graficos(evento) {
    evento.preventDefault();
    const botao = document.getElementById("button_graficos");
    const ativar = !botao.classList.contains("programmers_ativo");
    const [origem, destino] = ativar ? ["/new/", "/classic/"] : ["/classic/", "/new/"];

    document.querySelectorAll("img[data-legado]").forEach(imagem => {
        const caminho = imagem.getAttribute("src");
        if (caminho.includes(origem))
            imagem.setAttribute("src", caminho.replace(origem, destino));
    });

    const url = new URL(window.location.href);
    if (ativar) url.searchParams.set("dg", "true");
    else url.searchParams.delete("dg");
    history.replaceState(null, "", url);

    botao.classList.toggle("programmers_ativo", ativar);
    botao.textContent = ativar ? "Gráficos padrões" : "Programmer Art";
    const dica = ativar ? "Volte para os sprites atuais do Minecraft" : "Os gráficos originais do Minecraft";
    botao.setAttribute("onmouseover", `toolTip('${dica}')`);
    toolTip(dica);
    // O link do botão leva ao estado oposto ao atual, como na renderização do servidor
    const link_botao = new URL(botao.getAttribute("href"), window.location.href);
    if (ativar) link_botao.searchParams.delete("dg");
    else link_botao.searchParams.set("dg", "true");
    botao.setAttribute("href", link_botao.pathname.split("/").pop() + link_botao.search);

    // Mantém a escolha nos links de navegação entre versões
    document.querySelectorAll("#menu_user a[href*='versao_jogo=']").forEach(link => {
        if (link === botao) return;
        const destino_link = new URL(link.getAttribute("href"), window.location.href);
        if (ativar) destino_link.searchParams.set("dg", "true");
        else destino_link.searchParams.delete("dg");
        link.setAttribute("href", destino_link.pathname.split("/").pop() + destino_link.search);
    });
}

// Troca de versão na máquina do tempo sem recarregar a página
let navegando_versao = false

async function carrega_versao(url) {
    const resposta = await fetch(url, { cache: "no-store" })
    const novo = new DOMParser().parseFromString(await resposta.text(), "text/html")

    const novo_menu = novo.getElementById("menu_completo")
    const novo_input = novo.getElementById("input_versao_jogo")
    if (!novo_menu || !novo_input) throw new Error("resposta inválida");

    ["menu_completo", "estatisticas_inventario", "lista_versoes", "menu_user", "infos_versao"].forEach(id => {
        const atual = document.getElementById(id)
        const atualizado = novo.getElementById(id)
        if (atual && atualizado) atual.replaceWith(document.importNode(atualizado, true))
    })

    document.getElementById("input_versao_jogo").value = novo_input.value
    itens_ocultos = 0
    cache_pesquisa = null
    pesquisa = 0

    document.querySelectorAll(".opcoes_laterais, .opcoes_baixo, #img_configs_2, #img_coletaveis_2, #img_ocultos_2, #img_versoes_2")
        .forEach(elemento => { elemento.style.animation = "none" })

    inicia_arraste_scroll()
    try { filtragem(10, 0) } finally { sincroniza_tema(parseInt(novo_input.value), 1) }
}

function navega_versao(evento) {
    evento.preventDefault()
    if (navegando_versao) return

    const url = evento.currentTarget.href
    navegando_versao = true
    carrega_versao(url)
        .then(() => history.pushState(null, "", url))
        .catch(() => { window.location.href = url })
        .finally(() => { navegando_versao = false })
}

window.addEventListener("popstate", () => {
    if (window.location.pathname.endsWith("visualizacao.php"))
        carrega_versao(window.location.href).catch(() => window.location.reload())
})

// Garante que nenhuma imagem (inclusive as criadas depois) possa ser arrastada
document.addEventListener("dragstart", evento => {
    if (evento.target.tagName === "IMG") evento.preventDefault()
})
