drop table if exists meal;
drop table if exists daily_demand;
drop table if exists food;
drop table if exists observation;
drop table if exists weight;
drop table if exists poop;
drop table if exists pee;
drop table if exists medicine_dose;
drop table if exists medicine_application;
drop table if exists medicine;
drop table if exists cats;

create table cats
(
    id   tinyint unsigned not null primary key auto_increment,
    name varchar(50)      not null
) engine = InnoDB;

create table food
(
    id          smallint unsigned not null primary key auto_increment,
    name        varchar(30)       not null,
    description varchar(1000)     not null,
    visible     boolean           not null
) engine = InnoDB;

create table daily_demand
(
    id        smallint unsigned not null primary key auto_increment,
    timestamp datetime          not null,
    cat_id    tinyint unsigned  not null,
    foreign key (cat_id) references cats (id),
    food_id   smallint unsigned not null,
    foreign key (food_id) references food (id),
    weight    smallint unsigned not null
) engine = InnoDB;

create table meal
(
    id           int unsigned      not null primary key auto_increment,
    cat_id       tinyint unsigned  not null,
    foreign key (cat_id) references cats (id),
    food_id      smallint unsigned not null,
    foreign key (food_id) references food (id),
    start        datetime          not null,
    start_weight smallint unsigned not null,
    end          datetime          null,
    end_weight   smallint unsigned null
) engine = InnoDB;

create index ix_meal_cat_end on meal (cat_id, end);

create index ix_meal_cat_start on meal (cat_id, start);

create index ix_meal_start on meal (start);

create table poop
(
    id        smallint unsigned not null primary key auto_increment,
    cat_id    tinyint unsigned  not null,
    foreign key (cat_id) references cats (id),
    timestamp datetime          not null
) engine = InnoDB;

create index ix_poop on poop (cat_id, timestamp desc);

create table pee
(
    id        smallint unsigned not null primary key auto_increment,
    cat_id    tinyint unsigned  not null,
    foreign key (cat_id) references cats (id),
    timestamp datetime          not null
) engine = InnoDB;

create index ix_pee on pee (cat_id, timestamp desc);

create table observation
(
    id        smallint unsigned not null primary key auto_increment,
    cat_id    tinyint unsigned  not null,
    foreign key (cat_id) references cats (id),
    timestamp datetime          not null,
    notes     varchar(250)      not null
) engine = InnoDB;

create table weight
(
    id     smallint unsigned not null primary key auto_increment,
    cat_id tinyint unsigned  not null,
    foreign key (cat_id) references cats (id),
    date   date              not null,
    weight decimal(2, 1)     not null
) engine = InnoDB;

create table medicine
(
    id   smallint unsigned not null primary key auto_increment,
    name varchar(30)
) engine = InnoDB;

create table medicine_dose
(
    id          smallint unsigned not null primary key auto_increment,
    cat_id      tinyint unsigned  not null,
    foreign key (cat_id) references cats (id),
    name        varchar(30)       not null,
    medicine_id smallint unsigned not null,
    foreign key (medicine_id) references medicine (id),
    dose        decimal(7, 4)     not null,
    unit        varchar(6)        not null,
    day_count   tinyint unsigned  not null,
    visible     boolean           not null
) engine = InnoDB;

create table medicine_application
(
    id          smallint unsigned not null primary key auto_increment,
    medicine_id smallint unsigned not null,
    foreign key (medicine_id) references medicine (id),
    cat_id      tinyint unsigned  not null,
    foreign key (cat_id) references cats (id),
    timestamp   datetime          not null,
    dose        decimal(7, 4)     not null,
    unit        varchar(6)        not null
) engine = InnoDB;

create index ix_medicine_application on medicine_application (cat_id, timestamp, medicine_id);

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
