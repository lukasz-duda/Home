drop table if exists cats;

create table cats
(
    id   int unsigned not null primary key auto_increment,
    name varchar(50)  not null
);

drop table if exists food;

create table food
(
    id          int unsigned  not null primary key auto_increment,
    name        varchar(30)   not null,
    description varchar(1000) not null,
    visible     boolean       not null
);

drop table if exists daily_demand;

create table daily_demand
(
    timestamp datetime          not null,
    cat_id    int unsigned      not null,
    food_id   int unsigned      not null,
    weight    smallint unsigned not null
);

drop table if exists meal;

create table meal
(
    id           int unsigned      not null primary key auto_increment,
    cat_id       int unsigned      not null,
    food_id      int unsigned      not null,
    start        datetime          not null,
    start_weight smallint unsigned not null,
    end          datetime          null,
    end_weight   smallint unsigned null
);

drop table if exists poop;

create table poop
(
    cat_id    int unsigned not null,
    timestamp datetime     not null
);

drop table if exists pee;

create table pee
(
    cat_id    int unsigned not null,
    timestamp datetime     not null
);

drop table if exists observation;

create table observation
(
    cat_id    int unsigned not null,
    timestamp datetime     not null,
    notes     varchar(250) not null
);

drop table if exists weight;

create table weight
(
    cat_id int unsigned  not null,
    date   date          not null,
    weight decimal(2, 1) not null
);

drop table if exists medicine;

create table medicine
(
    id   smallint unsigned not null primary key auto_increment,
    name varchar(30)
);

drop table if exists medicine_dose;

create table medicine_dose
(
    id          tinyint unsigned not null primary key auto_increment,
    cat_id      int unsigned     not null,
    name        varchar(30)      not null,
    medicine_id int unsigned     not null,
    dose        decimal(6, 4)    not null,
    unit        varchar(2)       not null,
    day_count   tinyint unsigned not null
);

drop table if exists medicine_application;

create table medicine_application
(
    medicine_id smallint unsigned not null,
    cat_id      int unsigned      not null,
    timestamp   datetime          not null,
    dose        decimal(6, 4)     not null,
    unit        varchar(2)        not null
);

insert into cats (id, name)
values (1, 'Szyszka');
insert into cats (id, name)
values (2, 'Mgiełka');

insert into pee (cat_id, timestamp)
values (1, DATE_SUB(current_timestamp(), INTERVAL 27 HOUR));
insert into poop (cat_id, timestamp)
values (1, DATE_SUB(current_timestamp(), INTERVAL 75 HOUR));

insert into pee (cat_id, timestamp)
values (2, DATE_SUB(current_timestamp(), INTERVAL 23 HOUR));
insert into poop (cat_id, timestamp)
values (2, DATE_SUB(current_timestamp(), INTERVAL 71 HOUR));

insert into medicine (id, name)
values (1, 'Nieokreślony');