drop table if exists cars;

create table cars
(
    id      int unsigned       not null primary key auto_increment,
    name    varchar(50)        not null,
    vin     char(17)           not null
);

drop table if exists car_expenses;

create table car_expenses
(
    id            int unsigned   not null primary key auto_increment,
    car_id        int unsigned   not null,
    name          varchar(100)   not null,
    company_id    int unsigned,
    value         float unsigned not null,
    timestamp     datetime       not null,
    fuel_quantity float unsigned
);

drop table if exists mileage;

create table mileage
(
    car_id  int unsigned       not null,
    date    date               not null,
    mileage mediumint unsigned not null
);

drop table if exists companies;

create table companies
(
    id    int unsigned not null primary key auto_increment,
    name  varchar(100) not null,
    notes varchar(500)
);

drop table if exists car_tasks;

create table car_tasks
(
    id                  int unsigned     not null primary key auto_increment,
    car_id              int unsigned     not null,
    name                varchar(250)     not null,
    priority            tinyint unsigned not null,
    last_execution_date date,
    execution_interval  smallint unsigned,
    last_mileage        mediumint unsigned,
    mileage_interval    mediumint unsigned,
    notes               varchar(500)
);