create database minecraft_itens;
use minecraft_itens;

create table item(
	id_item int(11) not null primary key auto_increment,
    nome varchar(200) not null,
    descricao varchar(500),
    abamenu varchar(200) not null,
    empilhavel int(11) not null,
    coletavelSurvival tinyint(1),
    nome_icon varchar(500),
    renovavel tinyint(1),
    oculto_invt tinyint(1),
    programmer_art tinyint(1),
    posicao_item int(11),
    nome_interno varchar(150),
    versao_adicionada int,
    fabricavel int,
	aliases_nome varchar(1000)
);

create table cor_item(
    id_cor int not null auto_increment primary key,
    id_item int not null,
    tipo_item int not null,
    foreign key(id_item) references item(id_item)
);

create table durabilidade_item(
    id_durabilidade int not null auto_increment primary key,
    id_item int not null,
    durabilidade int not null,
    foreign key(id_item) references item(id_item)
);

create table crafting_item(
    id_craft int not null auto_increment primary key,
    craft varchar(500) not null
);