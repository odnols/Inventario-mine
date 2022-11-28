function toolTip(nome, descricao, nome_interno, cor_item, local) {

    let id_nome_item, id_descricao_item, id_nome_interno

    if (typeof nome != "undefined") {

        id_nome_item = "nome_item_minetip"
        id_descricao_item = "descricao_item_minetip"
        id_nome_interno = "nome_interno_minetip"

        if (typeof local !== "undefined") {
            id_nome_item = "nome_item_minetp"
            id_descricao_item = "descricao_item_minetp"
            id_nome_interno = "nome_interno_minetp"
        }

        const alvo_tooltip = document.getElementById(id_nome_item)
        const alvo_id_descricao_item = document.getElementById(id_descricao_item)

        if (alvo_tooltip) alvo_tooltip.innerHTML = ""
        if (alvo_id_descricao_item) alvo_id_descricao_item.innerHTML = ""

        const itens_especiais = ["", "item_encantado", "item_especial", "item_lendario"]
        const cores_efeitos = ["&1", "&2", "&3", "&4", "&r"]
        const nome_cores_efeitos = ["efeito_cor_azul", "efeito_cor_vermelha", "efeito_cor_roxa", "efeito_cor_verde", "efeito_cor_padrao"]

        if (alvo_tooltip)
            if (cor_item > 0)
                document.getElementById(id_nome_item).innerHTML += `<span class='${itens_especiais[cor_item]}'>${nome}</span>`
            else
                document.getElementById(id_nome_item).innerHTML = nome

        if (typeof descricao !== "undefined") {
            if (descricao.indexOf("&") == -1) {
                document.getElementById(id_descricao_item).style.color = "#a8a8a8"
                document.getElementById(id_descricao_item).style.textShadow = "3px 3px 0 #2a2a2a"

                if (typeof descricao !== "undefined")
                    document.getElementById(id_descricao_item).innerHTML = descricao
                else
                    document.getElementById(id_descricao_item).innerHTML = ""
            } else {

                const alvos_replace = ["Construcao", "Decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Pocoes", "Especiais", "Generico"]

                if (pesquisa == 0) {
                    for (let i = 0; i < alvos_replace.length; i++)
                        descricao = descricao.replace(`[&1${alvos_replace[i]}`, "")
                } else {
                    categorias_exib = ["Blocos de construção", "Blocos decorativos", "Redstone", "Transportes", "Diversos", "Alimentos", "Ferramentas", "Combate", "Poções", "Especiais", "Genérico"]

                    alvo_alteracao = descricao.split(" ")
                    alvo_alteracao[0] = alvo_alteracao[0].replace("[&1", "")

                    let i = alvos_replace.indexOf(alvo_alteracao[0])
                    descricao = descricao.replace(alvo_alteracao[0], `[&1${categorias_exib[i]}`)
                }

                descricao_colorida = descricao.split("[")

                for (let j = 0; j < descricao_colorida.length; j++) {
                    for (let i = 0; i < cores_efeitos.length; i++) {
                        if (descricao_colorida[j].indexOf(cores_efeitos[i]) != -1) {

                            descricao_colorida[j] = descricao_colorida[j].replace(cores_efeitos[i], "", 1)

                            document.getElementById(id_descricao_item).innerHTML += `<span class='${nome_cores_efeitos[i]}'>${descricao_colorida[j]}</span><br>`

                            break
                        }

                        if (descricao_colorida[j] == "&s")
                            document.getElementById(id_descricao_item).innerHTML += "<div class='espaco'></div><br>"
                    }
                }
            }
        }

        if (typeof nome_interno !== "undefined")
            document.getElementById(id_nome_interno).innerHTML = `minecraft:${nome_interno}`
        else
            document.getElementById(id_nome_interno).innerHTML = ""

        if (typeof local === "undefined")
            document.getElementById("minetip-tooltip").style.display = "Block"

        // Verifica se existem muitos alvos para poder acompanhar o mouse            
        const verifica = document.getElementsByClassName("slot_item")
        const verifica_2 = document.getElementsByClassName("slot_item_crafting")

        if (verifica.length > 1 || verifica_2.length > 1) {
            $(".slot_item").on("mousemove", function (event) {
                $("#minetip-tooltip").css({ left: event.pageX + 30, top: event.pageY - 50 })
            })

            $(".slot_item_add").on("mousemove", function (event) {
                $("#minetip-tooltip").css({ left: event.pageX + 30, top: event.pageY - 50 })
            })

            $(".slot_item_crafting").on("mousemove", function (event) {
                $("#minetip-tooltip").css({ left: event.pageX + 30, top: event.pageY - 50 })
            })
        }
    } else
        document.getElementById("minetip-tooltip").style.display = "None"
}

function troca_tema(versao_jogo, local) {

    if (tema == null || tema == 1)
        localStorage.setItem('tema_invent_mc', "0")

    if (tema == 0)
        localStorage.setItem('tema_invent_mc', "1")

    sincroniza_tema(versao_jogo, local)
}

function sincroniza_tema(versao_jogo, local) {

    let tema = localStorage.getItem("tema_invent_mc")
    let alvo = "claro", caminho = ""

    if (local) caminho = "../"

    if (tema != null)
        tema = parseInt(tema)
    else
        tema = 1

    if (tema == 0) {
        alvo = "escuro"
        document.getElementById("icone_tema").innerHTML = "🌑"
        document.getElementById("bttn_troca_tema").style.backgroundColor = "rgb(39, 39, 45)"
        document.getElementById("filtro_colorido").style.background = "linear-gradient(0deg, rgba(0,0,0,0.9248074229691877) 0%, rgba(3, 26, 124, 0.67) 39%, rgba(0,212,255,0) 100%)"
    }

    if (alvo == "claro") {
        document.getElementById("icone_tema").innerHTML = "☀️"
        document.getElementById("bttn_troca_tema").style.backgroundColor = "white"
        document.getElementById("filtro_colorido").style.background = "linear-gradient(0deg, rgba(0,0,0,0.9248074229691877) 0%, rgba(9,121,99,0.6699054621848739) 39%, rgba(0,212,255,0) 100%)"
    }

    lista_templates = ["#prancheta_add", ".input_prancheta", "#barra_pesquisa_input", "#barra_scroll", ".slot_item", ".slot_item_add", "#menu_criacao", "#seta_direita_crafting", "#seta_esquerda_crafting", "#craft_prancheta", "#slot_craft_ativo", "#preview_item_craft", "#grid_crafting", ".slot_item_crafting"]
    nome_template = ["prancheta.png", "barra_prancheta.png", "barra_pesquisa.png", "scroll.png", "slot.png", "slot_add.png", "crafting.png", "seta_direita.png", "seta_esquerda.png", "craft.png", "slot_add.png", "grid_crafting.png", "grid_crafting.png", "slot_item_crafting.png"]

    lista_imagens = ["prancheta", "img_construcao", "img_decorativos", "img_redstone", "img_transportes", "img_diversos", "img_alimentos", "img_ferramentas", "img_combate", "img_pocoes", "img_especiais", "img_pesquisa", "barra_scroll_block", "menu", "img_ocultos", "img_craft_todos", "img_craft_ferramentas", "img_craft_blocos", "img_craft_diversos", "img_craft_redstone"]
    nome_arquivos = ["prancheta.png", "aba_construcao.png", "aba_decorativos.png", "aba_redstone.png", "aba_transportes.png", "aba_diversos.png", "aba_alimentos.png", "aba_ferramentas.png", "aba_combate.png", "aba_pocoes.png", "aba_especiais.png", "aba_pesquisa.png", "scroll_bloqueado.png", "menu.png", "aba_oculto.png", "aba_craft_todos.png", "aba_craft_ferramentas.png", "aba_craft_blocos.png", "aba_craft_diversos.png", "aba_craft_redstone.png"]

    if (typeof versao_jogo !== "undefined")
        if (versao_jogo <= 2) {
            nome_arquivos[nome_arquivos.indexOf("menu.png")] = "menu_classic.png"

            document.getElementById("titulo_aba").innerHTML = "Seleção de item"

            document.getElementById("titulo_aba").style.top = "90%"
            document.getElementById("listagem").style.width = "433px"
            document.getElementById("listagem").style.height = "486px"
            document.getElementById("menu_completo").style.top = "0px"
            document.getElementById("menu_completo").style.left = "7%"
            document.getElementById("lista_itens").style.left = "12px"
            document.getElementById("lista_itens").style.top = "-69px"
            document.getElementById("lista_itens").style.width = "433px"
            document.getElementById("lista_itens").style.height = "486px"
            document.getElementById("barra_rolagem").style.top = "69px"
            document.getElementById("barra_rolagem").style.right = "111px"
            document.getElementById("barra_rolagem").style.height = "480px"
        }
        else {
            botoes_menu = document.getElementsByClassName("botoes_menu")

            for (let i = 0; i < botoes_menu.length; i++)
                botoes_menu[i].style.display = "Block"
        }

    for (let i = 0; i < lista_imagens.length; i++) { // Imagens
        imagem = document.getElementById(lista_imagens[i])

        if (imagem != null)
            imagem.src = `${caminho}IMG/Interface/${alvo}/${nome_arquivos[i]}`
    }

    for (let i = 0; i < lista_templates.length; i++) {
        if (lista_templates[i].includes("#")) { // Elementos que utilizam ID

            lista_templates[i] = lista_templates[i].replace("#", "")

            objeto = document.getElementById(lista_templates[i])
            if (objeto != null)
                objeto.style.background = `url('${caminho}IMG/Interface/${alvo}/${nome_template[i]}') no-repeat`
        } else { // Elementos que estão referenciados como classes

            lista_templates[i] = lista_templates[i].replace(".", "")
            let alvos = document.getElementsByClassName(lista_templates[i])

            for (let j = 0; j < alvos.length; j++)
                alvos[j].style.background = `url('${caminho}IMG/Interface/${alvo}/${nome_template[i]}') no-repeat`
        }
    }

    const textos = document.getElementsByClassName("cor_textos")
    const textos_craft = document.getElementsByClassName("textos_craft")

    const tam_textos = textos.length || textos_craft.length, alvos = textos || textos_craft

    const cores_texto = ["white", "#3f3f3f"]
    const titulo_aba = document.getElementById("titulo_aba")

    if (tema == 0 && titulo_aba)
        titulo_aba.style.color = "#ffffff"
    else if (titulo_aba)
        titulo_aba.style.color = "#3f3f3f"

    for (let i = 0; i < tam_textos; i++)
        alvos[i].style.color = cores_texto[tema]
}