drop table if exists car_expenses;
drop table if exists companies;
drop table if exists mileage;
drop table if exists car_task_executed;
drop table if exists car_tasks;
drop table if exists cars;

create table cars
(
    id   tinyint unsigned not null primary key auto_increment,
    name varchar(50)      not null,
    vin  char(17)         not null
) engine = InnoDB;

create table companies
(
    id      smallint unsigned not null primary key auto_increment,
    name    varchar(100)      not null,
    visible boolean           not null
) engine = InnoDB;

create table car_expenses
(
    id            int unsigned     not null primary key auto_increment,
    car_id        tinyint unsigned not null,
    foreign key (car_id) references cars (id),
    name          varchar(100)     not null,
    value         float unsigned   not null,
    timestamp     datetime         not null,
    fuel_quantity float unsigned,
    company_id    smallint unsigned,
    foreign key (company_id) references companies (id)
) engine = InnoDB;

create table mileage
(
    id      smallint unsigned  not null primary key auto_increment,
    car_id  tinyint unsigned   not null,
    foreign key (car_id) references cars (id),
    date    date               not null,
    mileage mediumint unsigned not null
) engine = InnoDB;

create table car_tasks
(
    id                  smallint unsigned not null primary key auto_increment,
    car_id              tinyint unsigned  not null,
    foreign key (car_id) references cars (id),
    name                varchar(250)      not null,
    priority            tinyint unsigned  not null,
    last_execution_date date,
    execution_interval  smallint unsigned,
    last_mileage        mediumint unsigned,
    mileage_interval    mediumint unsigned,
    notes               varchar(500)
) engine = InnoDB;

create table car_task_executed
(
    id          smallint unsigned  not null primary key auto_increment,
    car_task_id smallint unsigned  not null,
    foreign key (car_task_id) references car_tasks (id),
    date        date               not null,
    mileage     mediumint unsigned not null,
    notes       varchar(500)
) engine = InnoDB;