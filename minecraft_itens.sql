create database mc_itens;

use mc_itens;

create table item(
    id_item int(11) not null primary key auto_increment,
    nome varchar(200) not null,
    nome_icon varchar(500),
    nome_interno varchar(150),
    versao_adicionada varchar(10),
    empilhavel int(11),
    coletavel tinyint(1),
    renovavel tinyint(1),
    fabricavel tinyint(1)
);

create table item_titulo(
    id_cor int not null auto_increment primary key,
    id_item int not null,
    tipo_item int not null,
    foreign key(id_item) references item(id_item)
);

create table item_durabilidade(
    id_durabilidade int not null auto_increment primary key,
    id_item int not null,
    durabilidade int not null,
    foreign key(id_item) references item(id_item)
);

create table item_receita(
    id_craft int not null auto_increment primary key,
    id_item int not null,
    crafting varchar(500) not null,
    qtd_produtos int,
    tipo_craft int not null,
    foreign key(id_item) references item(id_item)
);

create table item_descricao(
    id_descricao int not null auto_increment primary key,
    id_item int not null,
    descricao varchar(500),
    foreign key(id_item) references item(id_item)
);

create table item_oculto(
    id_oculto int not null auto_increment primary key,
    id_item int not null,
    status_item tinyint(1),
    foreign key(id_item) references item(id_item)
);

create table item_legado(
    id_legado int not null auto_increment primary key,
    id_item int not null,
    status_item tinyint(1),
    foreign key(id_item) references item(id_item)
);

create table item_guia(
    id_guia int not null auto_increment primary key,
    id_item int not null,
    guia_versao varchar(1000),
    foreign key(id_item) references item(id_item)
);