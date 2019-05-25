drop table if exists cats;

create table cats
(
    id   int unsigned not null primary key auto_increment,
    name varchar(50)  not null
);

drop table if exists food;

create table food
(
    id          int unsigned not null primary key auto_increment,
    name        varchar(30)  not null,
    description varchar(250) not null,
    visible     bool         not null
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
    cat_id    int unsigned  not null,
    timestamp date          not null,
    weight    decimal(2, 1) not null
);