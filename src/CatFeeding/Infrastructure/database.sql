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
    description varchar(250) not null
);

drop table if exists daily_demand;

create table daily_demand
(
    timestamp timestamp        not null,
    cat_id    int unsigned     not null,
    food_id   int unsigned     not null,
    weight    tinyint unsigned not null
);

drop table if exists meal;

create table meal
(
    id           int unsigned     not null primary key auto_increment,
    cat_id       int unsigned     not null,
    food_id      int unsigned     not null,
    start        timestamp        not null,
    start_weight tinyint unsigned not null,
    end          timestamp        null,
    end_weight   tinyint unsigned null
);

drop table if exists poop;

create table poop
(
    cat_id    int unsigned not null,
    timestamp timestamp    not null
);

drop table if exists pee;

create table pee
(
    cat_id    int unsigned not null,
    timestamp timestamp    not null
);

drop table if exists observation;

create table observation
(
    cat_id    int unsigned not null,
    timestamp timestamp    not null,
    notes     varchar(250) not null
);