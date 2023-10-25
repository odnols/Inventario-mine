create database mc_itens;

use mc_itens;
select * from item_descricao order by id_item desc;

delete from item_descricao where id_item = 1497;

create table item(
    id_item int(11) not null primary key auto_increment,
    nome varchar(200) not null,
    icon varchar(500),
    internal varchar(150),
    versao varchar(10),
    tipo varchar(20),
    empilhavel int(11),
    coletavel boolean,
    renovavel boolean,
    fabricavel boolean
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
    historico_guias varchar(1000),
    foreign key(id_item) references item(id_item)
);