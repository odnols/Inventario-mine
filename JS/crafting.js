function inicia_craft(id_item) {
    window.location = `../modules/craft.php?id=${id_item}`;
}

function seleciona_item(id_item) {

    let novo_array = [];

    let slots_atalho = document.getElementById("slots_atalho_itens");
    slots_atalho.innerHTML = "";

    if (id_item !== "auto") {
        if (itens_atalho.includes(id_item)) // Removendo o item do elemento
            itens_atalho.splice(itens_atalho.indexOf(id_item), 1);
        else if (itens_atalho.length > 8)
            itens_atalho.pop();

        // Adicionando o item no primeiro lugar do array
        novo_array = [id_item].concat(itens_atalho);
        itens_atalho = novo_array;
    }

    if (dados_itens.length < 1)
        return sincroniza_itens(null, null, null, 1, id_item);

    if (id_item == "auto") // Utilizado para sincronizar os dados
        return;

    for (let i = 0; i < itens_atalho.length; i++) {

        let slot_craft_ativo = "";

        Object.keys(dados_itens).forEach(function (k) {
            if (itens_atalho[i] == dados_itens[k]["id_item"]) {
                sprite_item = `../IMG/Itens/new/${dados_itens[k]["tipo_item"]}/${dados_itens[k]["nome_icon"]}`;

                if (i == 0)
                    slot_craft_ativo = "id='slot_craft_ativo'";

                slots_atalho.innerHTML += `<div class='slot_item' ${slot_craft_ativo} onclick='seleciona_item(${itens_atalho[i]})'>
                    <img class='icon_item' src='${sprite_item}'>
                </div>`;
            }
        });
    }

    for (let i = 9 - itens_atalho.length; i > 0; i--) {
        slots_atalho.innerHTML += `<div class='slot_item'></div>`;
    }

    sincroniza_tema(undefined, 1);
}

function seta_item_craft(indice) {

    let grid_craft = document.getElementsByClassName("grid_craft");

    for (let i = 0; i < 9; i++) {
        if (i == indice) {
            if (grid_craft[i].style.backgroundImage == "")
                Object.keys(dados_itens).forEach(function (k) {
                    if (itens_atalho[0] == dados_itens[k]["id_item"]) {
                        sprite_item = `../IMG/Itens/new/${dados_itens[k]["tipo_item"]}/${dados_itens[k]["nome_icon"]}`;
                        grid_craft[i].style.backgroundImage = `url('${sprite_item}')`;
                        array_crafting[i] = itens_atalho[0];
                    }
                });
            else {
                grid_craft[i].style.backgroundImage = "";
                array_crafting[i] = null;
            }
        }
    }

    document.getElementById("array_craft").value = array_crafting;
}

function seta_item_criado(id_item) {

    Object.keys(dados_itens).forEach(function (k) {
        if (dados_itens[k]["id_item"] == id_item) {
            sprite_produto = `../IMG/Itens/new/${dados_itens[k]["tipo_item"]}/${dados_itens[k]["nome_icon"]}`;

            document.getElementById("sprite_produto").innerHTML = "";
            document.getElementById("sprite_produto").innerHTML += `<img src='${sprite_produto}' style='width: 48px'>`;

            // document.getElementById("qtd_produto").innerHTML = typeof qtd !== "undefined" ? qtd : null;
        }
    });
}

function mostra_crafting(receita, produto, qtd, local) {

    let caminho = '';
    if (local == 2 || local) {
        if (local == 2)
            document.getElementById("array_craft").value = receita;

        array_crafting = receita.split(",");
    }

    // Converte a string para um array legível
    if (receita.length == 9)
        receita = receita.split(",");

    if (local || local == 2)
        caminho = '../';

    const grid = document.getElementsByClassName('grid_craft');
    let sprite_produto = "";
    let dados_produto = "";

    if (dados_itens.length < 1)
        return sincroniza_itens(receita, produto, qtd);

    for (let i = 0; i < grid.length; i++) {

        let sprite_item = "";

        if (array_crafting[i] !== null || typeof array_crafting[i] !== "undefined") {
            Object.keys(dados_itens).forEach(function (k) {

                if (dados_itens[k]["id_item"] == array_crafting[i])
                    sprite_item = `${caminho}IMG/Itens/new/${dados_itens[k]["tipo_item"]}/${dados_itens[k]["nome_icon"]}`;

                if (dados_itens[k]["id_item"] == produto)
                    sprite_produto = `${caminho}IMG/Itens/new/${dados_itens[k]["tipo_item"]}/${dados_itens[k]["nome_icon"]}`;
            });

            if (typeof array_crafting[i] !== "undefined" && sprite_item.length > 0) // Altera o sprite para o item do grid
                grid[i].style.backgroundImage = `url('${sprite_item}')`;
        } else
            grid[i].style.backgroundImage = 'None';
    }

    Object.keys(dados_itens).forEach(function (k) {
        if (dados_itens[k]["id_item"] == produto) {

            dados_produto = dados_itens[k];

            sprite_produto = `${caminho}IMG/Itens/new/${dados_itens[k]["tipo_item"]}/${dados_itens[k]["nome_icon"]}`;
            nome_produto = dados_itens[k]["nome"];
        }
    });

    const slots_atalho = document.getElementById("slots_atalho_itens");

    if (slots_atalho) {
        slots_atalho.innerHTML = "";

        for (let i = 9 - itens_atalho.length; i > 0; i--) {
            slots_atalho.innerHTML += `<div class='slot_item'></div>`;
        }
    }

    if (produto !== null) {
        if (document.getElementById("click_abrir_crafting"))
            document.getElementById("click_abrir_crafting").setAttribute('onClick', `inicia_craft(${produto})`);

        document.getElementById("sprite_produto").innerHTML = "";
        document.getElementById("sprite_produto").innerHTML += `<img src='${sprite_produto}' style='width: 48px'>`;

        document.getElementById("sprite_produto").setAttribute('onMouseOver', `toolTip("${dados_produto.nome_item}", "${dados_produto.descricao}", "${dados_produto.nome_interno}", null)`);
        document.getElementById("sprite_produto").setAttribute('onMouseOut', `toolTip()`);

        // document.getElementById("qtd_produto").innerHTML = typeof qtd !== "undefined" ? qtd : null;
    }
}