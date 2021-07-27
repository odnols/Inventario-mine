function toolTip(nome, descricao){

    if(typeof nome != "undefined"){
        document.getElementById("nome_item_minetip").innerHTML = nome;
        
        if(typeof descricao != "undefined")
            document.getElementById("descricao_item_minetip").innerHTML = "minecraft:"+ descricao;
        else
            document.getElementById("descricao_item_minetip").innerHTML = "";
            
        document.getElementById("minetip-tooltip").style.display = "Block";

        $(".slot_item" ).on( "mousemove", function( event ) {
            $("#minetip-tooltip").css({left:event.pageX + 30, top:event.pageY - 50} )
        });

    }else{
        document.getElementById("minetip-tooltip").style.display = "None";
    }
}