function toolTip(nome, descricao, tipo_item, local){

    if(typeof nome != "undefined"){

        if(typeof local != "undefined"){
            id_nome_item = "nome_item_minetp";
            id_descricao_item = "descricao_item_minetp";
        }else{
            id_nome_item = "nome_item_minetip";
            id_descricao_item = "descricao_item_minetip";
        }

        document.getElementById(id_nome_item).innerHTML = "";

        if(tipo_item == 1)
            document.getElementById(id_nome_item).innerHTML += "<span class='item_encantado'>"+ nome +"</span>";
        else if(tipo_item == 2)
            document.getElementById(id_nome_item).innerHTML += "<span class='item_especial'>"+ nome +"</span>";
        else if(tipo_item == 3)
            document.getElementById(id_nome_item).innerHTML += "<span class='item_lendario'>"+ nome +"</span>";
        else
            document.getElementById(id_nome_item).innerHTML = nome;
        
        if(typeof descricao != "undefined")
            document.getElementById(id_descricao_item).innerHTML = "minecraft:"+ descricao;
        else
            document.getElementById(id_descricao_item).innerHTML = "";
        
        if(typeof local == "undefined")
            document.getElementById("minetip-tooltip").style.display = "Block";

        $(".slot_item" ).on( "mousemove", function( event ) {
            $("#minetip-tooltip").css({left:event.pageX + 30, top:event.pageY - 50} )
        });

    }else{
        document.getElementById("minetip-tooltip").style.display = "None";
    }
}