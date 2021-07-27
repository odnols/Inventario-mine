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

        let cores = ["", "item_encantado", "item_especial", "item_lendario"];
        let cores_efeitos = ["&1", "&2", "&3", "&4"];
        let nome_cores_efeitos = ["efeito_cor_azul", "efeito_cor_vermelha", "efeito_cor_roxa", "efeito_cor_verde"];

        if(cor_item > 0)
            document.getElementById(id_nome_item).innerHTML += "<span class='"+ cores[cor_item] +"'>"+ nome +"</span>";
        else
            document.getElementById(id_nome_item).innerHTML = nome;
            
        if(descricao.indexOf("&") == -1){
            document.getElementById(id_descricao_item).style.color = "#a8a8a8";
            document.getElementById(id_descricao_item).style.textShadow = "3px 3px 0 #2a2a2a";

            if(typeof descricao != "undefined")
                document.getElementById(id_descricao_item).innerHTML = descricao;
            else
                document.getElementById(id_descricao_item).innerHTML = "";
        }else{

            descricao_colorida = descricao.split("[");
            console.log(descricao_colorida);
            
            for(var j = 0; j < descricao_colorida.length; j++){
                for(var i = 0; i < cores_efeitos.length; i++){
                    if(descricao_colorida[j].indexOf(cores_efeitos[i]) != -1){
                        
                        descricao_colorida[j] = descricao_colorida[j].replace(cores_efeitos[i], "", 1);

                        document.getElementById(id_descricao_item).innerHTML += "<span class='"+ nome_cores_efeitos[i] +"'> "+ descricao_colorida[j] +"</span><br>";
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

        $(".slot_item").on("mousemove", function( event ) {
            $("#minetip-tooltip").css({left:event.pageX + 30, top:event.pageY - 50} )
        });

    }else{
        document.getElementById("minetip-tooltip").style.display = "None";
    }
}