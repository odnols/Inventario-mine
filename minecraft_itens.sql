create database minecraft_itens;
use minecraft_itens;

create table item(
	id_item int(11) not null primary key auto_increment,
    nome varchar(200) not null,
    abamenu varchar(200) not null,
    empilhavel int(11) not null,
    coletavelSurvival tinyint(1),
    img varchar(500),
    renovavel tinyint(1),
    posicao_item int(11),
    nome_interno varchar(150),
    versao_adicionada varchar(150),
	aliases_nome varchar(1000)
);