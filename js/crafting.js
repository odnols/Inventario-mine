function inicia_craft(id_item) {
    window.location = `../modules/craft.php?id=${id_item}`
}

var item_na_mao_vazio = false
var configuracoes_craft = null
const tipos_craft = { bancada: 9, ferraria: 3, fornalha: 2, pocao: 4 }
var indices_receitas_craft = { bancada: 0, ferraria: 0, fornalha: 0, pocao: 0 }

function receita_em_slots(receita) {
    const valores = Array.isArray(receita) ? receita : String(receita || "").split(",")
    return Array.from({ length: 9 }, (_, indice) => {
        const idItem = Number(valores[indice])
        return Number.isInteger(idItem) && idItem > 0 ? idItem : null
    })
}

function limita_slots_por_tipo(receita, tipo) {
    const quantidadeSlots = tipos_craft[tipo]
    return receita.map((item, indice) => indice < quantidadeSlots ? item : null)
}

function configura_tooltip_slot(slot, item) {
    if (!item) {
        slot.onmouseover = slot.onmousemove = slot.onmouseout = null
        return
    }

    slot.onmouseover = evento => {
        toolTip(item.name, null, item.internal_name || undefined, null)
        posiciona_tooltip(evento)
    }
    slot.onmousemove = posiciona_tooltip
    slot.onmouseout = () => toolTip()
}

function renderiza_previews_craft_item() {
    const preview = document.getElementById("preview_item_craft")
    if (!preview)
        return

    const itens = new Map(
        Array.from(preview.querySelectorAll("[data-craft-item-id]"), elemento => [
            Number(elemento.dataset.craftItemId),
            {
                type: elemento.dataset.craftItemType,
                icon: elemento.dataset.craftItemIcon,
                name: elemento.dataset.craftItemName,
                internal_name: elemento.dataset.craftItemInternalName
            }
        ])
    )
    const receitas = Array.from(preview.querySelectorAll(".craft_recipe_preview"))
    receitas.forEach(receita => {
        const tipo = receita.dataset.craftType
        const idsReceita = receita.dataset.craftRecipe.split(",")
        const produto = receita.querySelector(".craft_recipe_output img")
        if (produto) {
            configura_tooltip_slot(produto, {
                name: produto.dataset.productName,
                internal_name: produto.dataset.productInternalName
            })
        }
        receita.querySelectorAll(".grid_craft").forEach((slot, indice) => {
            const idItem = Number(idsReceita[indice])
            const item = Number.isInteger(idItem) && idItem > 0 ? itens.get(idItem) : null
            const slotAtivo = indice < tipos_craft[tipo]

            slot.style.display = slotAtivo ? "block" : "none"
            slot.style.backgroundImage = item
                ? `url('../img/itens/new/${item.type}/${item.icon}')`
                : "none"
            configura_tooltip_slot(slot, item)
        })
    })

    if (receitas.length > 1) {
        let indice = 0
        const indicador = preview.querySelector(".craft_preview_indicador")
        const mostraReceita = proximo => {
            toolTip()
            receitas[indice].hidden = true
            indice = (proximo + receitas.length) % receitas.length
            receitas[indice].hidden = false
            indicador.textContent = `${indice + 1} / ${receitas.length}`
        }
        preview.querySelector(".craft_preview_anterior").addEventListener("click", () => mostraReceita(indice - 1))
        preview.querySelector(".craft_preview_proxima").addEventListener("click", () => mostraReceita(indice + 1))
        setInterval(() => {
            if (document.hidden || preview.matches(":hover") || preview.contains(document.activeElement))
                return
            mostraReceita(indice + 1)
        }, 4000)
    }
}

function salva_configuracao_craft(tipo, configurada) {
    if (!configuracoes_craft || !configuracoes_craft[tipo])
        return

    const campoQuantidade = document.getElementById("input_qtd_produto")
    const configuracao = configuracoes_craft[tipo][indices_receitas_craft[tipo]]

    configuracao.recipe = receita_em_slots(array_crafting)
    if (campoQuantidade && Number.isInteger(Number(campoQuantidade.value)) && Number(campoQuantidade.value) > 0)
        configuracao.products = Number(campoQuantidade.value)
    if (configurada)
        configuracao.configured = true

}

function atualiza_seletor_receitas_craft(tipo) {
    const receitas = configuracoes_craft[tipo]
    const indice = indices_receitas_craft[tipo]
    document.getElementById("receita_craft_indicador").textContent = `Receita ${indice + 1} / ${receitas.length}`
    document.getElementById("receita_craft_anterior").disabled = indice === 0
    document.getElementById("receita_craft_proxima").disabled = indice === receitas.length - 1
}

function muda_receita_craft(acao) {
    const tipo = document.getElementById("tipo_craft").value
    salva_configuracao_craft(tipo, false)
    const receitas = configuracoes_craft[tipo]
    if (acao === "nova") {
        receitas.push({ id: null, recipe: Array(9).fill(null), products: 1, configured: false })
        indices_receitas_craft[tipo] = receitas.length - 1
    } else {
        indices_receitas_craft[tipo] = Math.max(0, Math.min(receitas.length - 1, indices_receitas_craft[tipo] + acao))
    }
    seleciona_tipo_craft(tipo, true)
}

function inicializa_configuracoes_craft() {
    const formCraft = document.getElementById("craft_prancheta")
    const campoTipo = document.getElementById("tipo_craft")
    const campoQuantidade = document.getElementById("input_qtd_produto")
    const campoConfiguracoes = document.getElementById("craft_configs")
    if (!formCraft || !campoTipo || !campoConfiguracoes)
        return

    const configuracoesSalvas = JSON.parse(formCraft.dataset.craftConfigs || "{}")
    const tipoInicial = formCraft.dataset.tipoCraft
    configuracoes_craft = {}

    for (const tipo of Object.keys(tipos_craft)) {
        const salvas = configuracoesSalvas[tipo] || []
        configuracoes_craft[tipo] = salvas.length
            ? salvas.map(salva => {
                const recipe = limita_slots_por_tipo(receita_em_slots(salva.recipe), tipo)
                const products = Math.max(1, Number(salva.products) || 1)
                return {
                    id: salva.id, recipe, products, configured: true,
                    original: JSON.stringify({ recipe, products })
                }
            })
            : [{
                id: null,
                recipe: Array(9).fill(null),
                products: tipo === tipoInicial && campoQuantidade
                    ? Math.max(1, Number(campoQuantidade.value) || 1)
                    : 1,
                configured: false
            }]
    }

    campoTipo.value = tipoInicial
    array_crafting = configuracoes_craft[tipoInicial][0].recipe.slice()

    campoQuantidade?.addEventListener("input", () => {
        salva_configuracao_craft(campoTipo.value, true)
    })

    document.getElementById("receita_craft_anterior").addEventListener("click", () => muda_receita_craft(-1))
    document.getElementById("receita_craft_proxima").addEventListener("click", () => muda_receita_craft(1))
    document.getElementById("receita_craft_nova").addEventListener("click", () => muda_receita_craft("nova"))

    formCraft.addEventListener("submit", () => {
        salva_configuracao_craft(campoTipo.value, false)
        const dados = []
        Object.keys(configuracoes_craft).forEach(tipo => {
            configuracoes_craft[tipo].forEach(configuracao => {
                const temIngrediente = tipo === "fornalha"
                    ? configuracao.recipe[0] !== null
                    : configuracao.recipe.some(id => id !== null)
                const alterada = configuracao.original !== JSON.stringify({
                    recipe: configuracao.recipe, products: configuracao.products
                })
                if ((configuracao.id !== null && alterada)
                    || (configuracao.id === null && configuracao.configured && temIngrediente)) {
                    dados.push({
                        id: configuracao.id,
                        type: tipo,
                        recipe: configuracao.recipe,
                        products: configuracao.products
                    })
                }
            })
        })
        campoConfiguracoes.value = JSON.stringify(dados)
    })
}

function seleciona_tipo_craft(tipo, pularSalvamento) {
    const campoTipo = document.getElementById("tipo_craft")
    const opcoes = document.querySelectorAll(".tipo_craft_opcao")
    const craftPrancheta = document.getElementById("craft_prancheta")

    if (!campoTipo || !opcoes.length)
        return

    if (!Object.hasOwnProperty.call(tipos_craft, tipo))
        throw new Error(`Tipo de crafting inválido: ${tipo}`)
    const tipoValido = tipo
    const tipoAnterior = campoTipo.value
    if (configuracoes_craft && !pularSalvamento)
        salva_configuracao_craft(tipoAnterior, false)

    campoTipo.value = tipoValido
    if (craftPrancheta)
        craftPrancheta.dataset.tipoCraft = tipoValido

    if (configuracoes_craft) {
        const configuracao = configuracoes_craft[tipoValido][indices_receitas_craft[tipoValido]]
        configuracao.recipe = limita_slots_por_tipo(configuracao.recipe, tipoValido)
        array_crafting = configuracao.recipe.slice()
        const campoQuantidade = document.getElementById("input_qtd_produto")
        if (campoQuantidade)
            campoQuantidade.value = configuracao.products
        atualiza_seletor_receitas_craft(tipoValido)
    }

    if (craftPrancheta)
        craftPrancheta.style.backgroundImage = `url('../img/interface/claro/craft_${tipoValido}.png')`

    const temaCraft = localStorage.getItem("tema_invent_mc") === "0" ? "escuro" : "claro"
    const gradeCraft = document.getElementById("grid_crafting")
    const produtoFinal = document.getElementById("produto_final")
    if (gradeCraft)
        gradeCraft.style.background = tipoValido === "bancada"
            ? `url('../img/interface/${temaCraft}/mask_bancada.png') no-repeat`
            : "none"
    if (produtoFinal)
        produtoFinal.style.background = "none"

    opcoes.forEach(opcao => {
        const selecionada = opcao.dataset.tipoCraft === tipoValido
        opcao.classList.toggle("ativa", selecionada)
        opcao.setAttribute("aria-pressed", selecionada ? "true" : "false")
    })

    const campoReceita = document.getElementById("array_craft")
    if (campoReceita)
        campoReceita.value = array_crafting.join(",")

    if (tipoValido === "fornalha")
        aplica_carvao_como_combustivel()

    const produto = document.getElementById("sprite_produto")?.dataset.productId
    mostra_crafting(array_crafting.join(","), produto || null, null, 2)
}

function aplica_carvao_como_combustivel() {
    if (document.getElementById("tipo_craft")?.value !== "fornalha"
        || (array_crafting[1] !== null && typeof array_crafting[1] !== "undefined" && array_crafting[1] !== "")
    )
        return

    const carvao = dados_itens.find(item => item.internal_name === "coal")
    if (!carvao)
        return

    array_crafting[1] = carvao.id
    salva_configuracao_craft("fornalha", false)

    const slotCombustivel = document.getElementsByClassName("grid_craft")[1]
    if (slotCombustivel) {
        slotCombustivel.style.backgroundImage = `url('../img/itens/new/${carvao.type}/${carvao.icon}')`
        configura_tooltip_slot(slotCombustivel, carvao)
    }

    const campoReceita = document.getElementById("array_craft")
    if (campoReceita)
        campoReceita.value = array_crafting.join(",")
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

    if (document.getElementById("tipo_craft")?.value === "fornalha" && indice === 1)
        aplica_carvao_como_combustivel()

    configura_tooltip_slot(
        slot,
        dados_itens.find(item => item.id == array_crafting[indice])
    )
    salva_configuracao_craft(document.getElementById("tipo_craft")?.value, true)

    const craftInput = document.getElementById("array_craft")
    if (craftInput)
        craftInput.value = array_crafting.join(",")
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

    aplica_carvao_como_combustivel()

    for (let i = 0; i < grid.length; i++) {

        let sprite_item = ""
        let dados_item = null

        if (array_crafting[i] !== null || typeof array_crafting[i] !== "undefined") {
            Object.keys(dados_itens).forEach(function (k) {

                if (dados_itens[k]["id"] == array_crafting[i]) {
                    sprite_item = `${caminho}img/itens/new/${dados_itens[k]["type"]}/${dados_itens[k]["icon"]}`
                    dados_item = dados_itens[k]
                }

                if (dados_itens[k]["id"] == produto)
                    sprite_produto = `${caminho}img/itens/new/${dados_itens[k]["type"]}/${dados_itens[k]["icon"]}`
            })

            if (sprite_item.length > 0) // Altera o sprite para o item do grid
                grid[i].style.backgroundImage = `url('${sprite_item}')`
        } else
            grid[i].style.backgroundImage = 'none'

        configura_tooltip_slot(grid[i], dados_item)
    }

    salva_configuracao_craft(document.getElementById("tipo_craft")?.value, false)
    const campoReceita = document.getElementById("array_craft")
    if (campoReceita)
        campoReceita.value = array_crafting.join(",")

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