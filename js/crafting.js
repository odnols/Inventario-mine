function inicia_craft(id_item) {
    window.location = `../modules/craft.php?id=${id_item}`
}

var item_na_mao_vazio = false

function seleciona_tipo_craft(tipo, preservarReceita) {
    const campoTipo = document.getElementById("tipo_craft")
    const opcoes = document.querySelectorAll(".tipo_craft_opcao")
    const craftPrancheta = document.getElementById("craft_prancheta")

    if (!campoTipo || !opcoes.length)
        return

    const tipoValido = [0, 1, 2, 3].includes(Number(tipo)) ? Number(tipo) : 0
    const tipoAnterior = Number(campoTipo.value)
    campoTipo.value = tipoValido
    if (craftPrancheta)
        craftPrancheta.dataset.tipoCraft = tipoValido

    const temaCraft = localStorage.getItem("tema_invent_mc") === "0" ? "escuro" : "claro"
    const gradeCraft = document.getElementById("grid_crafting")
    const produtoFinal = document.getElementById("produto_final")

    if (gradeCraft)
        gradeCraft.style.background = tipoValido === 0
            ? `url('../img/interface/${temaCraft}/grid_crafting.png') no-repeat`
            : "none"

    if (produtoFinal)
        produtoFinal.style.background = tipoValido === 0
            ? "none"
            : `url('../img/interface/${temaCraft}/slot.png') no-repeat center`

    opcoes.forEach(opcao => {
        const selecionada = Number(opcao.dataset.tipoCraft) === tipoValido
        opcao.classList.toggle("ativa", selecionada)
        opcao.setAttribute("aria-pressed", selecionada ? "true" : "false")
    })

    if (preservarReceita || tipoAnterior === tipoValido)
        return

    const numeroSlots = tipoValido === 0 || tipoValido === 3 ? 9 : tipoValido === 1 ? 3 : 1
    const gridCraft = document.getElementsByClassName("grid_craft")

    for (let indice = numeroSlots; indice < gridCraft.length; indice++) {
        gridCraft[indice].style.backgroundImage = "none"
        array_crafting[indice] = null
    }

    const campoReceita = document.getElementById("array_craft")
    if (campoReceita)
        campoReceita.value = array_crafting
}

document.addEventListener("DOMContentLoaded", function () {
    const indicador = document.createElement("img")
    indicador.id = "item_na_mao"
    indicador.hidden = true
    indicador.setAttribute("aria-hidden", "true")
    document.body.appendChild(indicador)
    atualiza_item_na_mao()

    document.addEventListener("mousemove", function (event) {
        if (!indicador.hidden) {
            indicador.style.left = `${event.clientX + 8}px`
            indicador.style.top = `${event.clientY + 8}px`
        }
    })

    document.querySelectorAll(".tipo_craft_opcao").forEach(opcao => {
        opcao.addEventListener("click", function () {
            seleciona_tipo_craft(opcao.dataset.tipoCraft)
        })
    })
})

function seleciona_item(id_item) {

    let slots_atalho = document.getElementById("slots_atalho_itens")

    if (id_item !== "auto") {
        item_na_mao_vazio = false
        itens_atalho = itens_atalho.filter(item => item != id_item)
        itens_atalho.unshift(id_item)
        itens_atalho = itens_atalho.slice(0, 9)
    }

    if (dados_itens.length < 1)
        return sincroniza_itens(null, null, null, 1, id_item)

    if (!slots_atalho)
        return

    slots_atalho.innerHTML = ""

    for (let i = 0; i < itens_atalho.length; i++) {

        let slot_craft_ativo = ""

        Object.keys(dados_itens).forEach(function (k) {
            if (itens_atalho[i] == dados_itens[k]["id"]) {
                sprite_item = `../img/itens/new/${dados_itens[k]["type"]}/${dados_itens[k]["icon"]}`

                if (i == 0)
                    slot_craft_ativo = "id='slot_craft_ativo'"

                slots_atalho.innerHTML += `<div class='slot_item' ${slot_craft_ativo} onclick='seleciona_item(${itens_atalho[i]})'>
                    <img class='icon_item' src='${sprite_item}' onmouseover='toolTip(\"${dados_itens[k]["name"]}\", null, \"${dados_itens[k]["internal_name"]}\")', onmouseout='toolTip()'>
                </div>`
            }
        })
    }

    for (let i = 9 - itens_atalho.length; i > 0; i--) {
        slots_atalho.innerHTML += `<div class='slot_item'></div>`
    }

    atualiza_item_na_mao()
    sincroniza_tema(undefined, 1)
}

function atualiza_item_na_mao() {
    const indicador = document.getElementById("item_na_mao")
    const abaRemover = document.getElementById("aba_remover_item_selecionado")

    const itemSelecionado = item_na_mao_vazio
        ? null
        : dados_itens.find(item => item.id == itens_atalho[0])

    if (!itemSelecionado) {
        if (indicador) {
            indicador.hidden = true
            indicador.removeAttribute("src")
        }
        if (abaRemover)
            abaRemover.style.display = "none"
        return
    }

    if (indicador) {
        indicador.src = `../img/itens/new/${itemSelecionado.type}/${itemSelecionado.icon}`
        indicador.alt = itemSelecionado.name
        indicador.hidden = false
    }

    if (abaRemover) {
        if (abaRemover.style.display !== "block")
            abaRemover.style.display = "block"
    }
}

function remove_item_selecionado() {
    if (itens_atalho.length > 0)
        itens_atalho.shift()

    item_na_mao_vazio = true
    seleciona_item("auto")
}

function seta_item_craft(indice) {

    const gridCraft = document.getElementsByClassName("grid_craft")
    const selectedItemId = itens_atalho[0]
    const slot = gridCraft[indice]

    if (!slot)
        return

    const selectedItem = item_na_mao_vazio
        ? null
        : dados_itens.find(item => item.id == selectedItemId)

    if (!selectedItem || array_crafting[indice] == selectedItem.id) {
        slot.style.backgroundImage = "none"
        array_crafting[indice] = null
    } else {
        const spriteItem = `../img/itens/new/${selectedItem.type}/${selectedItem.icon}`
        slot.style.backgroundImage = `url('${spriteItem}')`
        array_crafting[indice] = selectedItem.id
    }

    const craftInput = document.getElementById("array_craft")
    if (craftInput)
        craftInput.value = array_crafting
}

function seta_item_criado(id_item) {

    Object.keys(dados_itens).forEach(function (k) {
        if (dados_itens[k]["id"] == id_item) {
            sprite_produto = `../img/itens/new/${dados_itens[k]["type"]}/${dados_itens[k]["icon"]}`

            document.getElementById("sprite_produto").innerHTML = ""
            document.getElementById("sprite_produto").innerHTML += `<img src='${sprite_produto}' style='width: 48px'>`
        }
    })
}

function mostra_crafting(receita, produto, qtd, local) {

    receita = receita || ""

    let caminho = ''
    if (receita.length > 0) {
        if (local == 2 || local) {
            if (local == 2)
                document.getElementById("array_craft").value = receita

            array_crafting = receita.split(",")
        }
    } else if (local == 2) {
        array_crafting = [null, null, null, null, null, null, null, null, null]
        document.getElementById("array_craft").value = ""
    }

    if (local || local == 2)
        caminho = '../'

    const grid = document.getElementsByClassName('grid_craft')
    let sprite_produto = "", dados_produto = null

    for (let i = 0; i < grid.length; i++) {
        grid[i].style.backgroundImage = 'none'
    }

    if (dados_itens.length < 1)
        return sincroniza_itens(receita, produto, qtd)

    for (let i = 0; i < grid.length; i++) {

        let sprite_item = "", nome_item = "", nome_interno_item = ""

        if (array_crafting[i] !== null || typeof array_crafting[i] !== "undefined") {
            Object.keys(dados_itens).forEach(function (k) {

                if (dados_itens[k]["id"] == array_crafting[i]) {
                    sprite_item = `${caminho}img/itens/new/${dados_itens[k]["type"]}/${dados_itens[k]["icon"]}`
                    nome_item = dados_itens[k]["name"]
                    nome_interno_item = dados_itens[k]["internal_name"]
                }

                if (dados_itens[k]["id"] == produto)
                    sprite_produto = `${caminho}img/itens/new/${dados_itens[k]["type"]}/${dados_itens[k]["icon"]}`
            })

            if (sprite_item.length > 0) // Altera o sprite para o item do grid
                grid[i].style.backgroundImage = `url('${sprite_item}')`
        } else
            grid[i].style.backgroundImage = 'none'

        // Tooltip dos ingredientes na prévia da receita de item_detalhes
        if (grid[i].closest("#preview_item_craft")) {
            if (nome_item) {
                grid[i].onmouseover = evento => {
                    toolTip(nome_item, null, nome_interno_item || undefined, null)
                    posiciona_tooltip(evento)
                }
                grid[i].onmousemove = posiciona_tooltip
                grid[i].onmouseout = () => toolTip()
            } else
                grid[i].onmouseover = grid[i].onmousemove = grid[i].onmouseout = null
        }
    }

    dados_produto = dados_itens.find(item => item.id == produto)

    const produtoSlot = document.getElementById("sprite_produto")
    if (!dados_produto && produtoSlot && produtoSlot.dataset.productId == produto) {
        dados_produto = {
            type: produtoSlot.dataset.productType,
            icon: produtoSlot.dataset.productIcon,
            name: produtoSlot.dataset.productName,
            internal_name: produtoSlot.dataset.productInternalName
        }
    }

    if (dados_produto)
        sprite_produto = `${caminho}img/itens/new/${dados_produto.type}/${dados_produto.icon}`

    const slots_atalho = document.getElementById("slots_atalho_itens")

    if (slots_atalho) {
        slots_atalho.innerHTML = ""

        for (let i = 9 - itens_atalho.length; i > 0; i--) {
            slots_atalho.innerHTML += `<div class='slot_item'></div>`
        }
    }

    if (produto !== null && dados_produto && produtoSlot) {
        if (document.getElementById("click_abrir_crafting"))
            document.getElementById("click_abrir_crafting").setAttribute('onClick', `inicia_craft(${produto})`)

        const imagemProduto = document.createElement("img")
        imagemProduto.src = sprite_produto
        imagemProduto.alt = dados_produto.name
        imagemProduto.style.width = "48px"
        produtoSlot.replaceChildren(imagemProduto)

        produtoSlot.onmouseover = () => toolTip(dados_produto.name, null, dados_produto.internal_name, null)
        produtoSlot.onmouseout = () => toolTip()

    }

    const quantidadeProduto = document.getElementById("qtd_produto")
    if (quantidadeProduto && qtd !== null && typeof qtd !== "undefined")
        quantidadeProduto.textContent = qtd
}

document.addEventListener("DOMContentLoaded", function () {
    const list = document.getElementById("prancheta_itens_crafting")
    const search = document.getElementById("busca_crafting")
    const clearSearch = document.getElementById("limpar_busca_crafting")
    const pageLabel = document.getElementById("num_pagina_craft")
    const arrows = [
        document.getElementById("seta_esquerda_crafting"),
        document.getElementById("seta_direita_crafting")
    ]

    if (!list || !search || !clearSearch || !pageLabel || arrows.some(arrow => !arrow))
        return

    const items = Array.from(list.querySelectorAll("a[data-item-name]"))
    const pageSize = 20
    let filteredItems = items
    let currentPage = 0
    let isOpen = false

    const emptyMessage = document.createElement("p")
    emptyMessage.className = "crafting_sem_resultados"
    emptyMessage.textContent = "Nenhum item encontrado."
    emptyMessage.hidden = true
    list.appendChild(emptyMessage)

    function normalize(value) {
        return value.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLocaleLowerCase("pt-BR")
    }

    function renderItems() {
        const pageCount = Math.ceil(filteredItems.length / pageSize)
        currentPage = Math.min(currentPage, Math.max(pageCount - 1, 0))
        const firstItem = currentPage * pageSize
        const pageItems = new Set(filteredItems.slice(firstItem, firstItem + pageSize))

        items.forEach(item => {
            item.style.display = pageItems.has(item) ? "" : "none"
        })

        emptyMessage.hidden = filteredItems.length > 0
        pageLabel.textContent = pageCount ? `${currentPage + 1}/${pageCount}` : "0/0"
    }

    function openList() {
        if (isOpen)
            return

        isOpen = true
        list.style.display = "block"
        search.style.display = "block"
        clearSearch.style.display = "block"
        pageLabel.style.display = "block"
        arrows.forEach(arrow => arrow.setAttribute("aria-expanded", "true"))
        renderItems()
    }

    search.addEventListener("input", function () {
        const query = normalize(search.value.trim())
        filteredItems = items.filter(item => normalize(item.dataset.itemName).includes(query))
        currentPage = 0
        renderItems()
    })

    clearSearch.addEventListener("click", function () {
        search.value = ""
        search.dispatchEvent(new Event("input", { bubbles: true }))
        search.focus()
    })

    arrows.forEach((arrow, index) => {
        arrow.addEventListener("click", function (event) {
            event.preventDefault()

            if (!isOpen) {
                openList()
                return
            }

            const pageCount = Math.ceil(filteredItems.length / pageSize)

            if (index === 0)
                currentPage = Math.max(currentPage - 1, 0)
            else
                currentPage = Math.min(currentPage + 1, Math.max(pageCount - 1, 0))

            renderItems()
        })
    })

    openList()
})