drop table if exists expenses;

create table expenses
(
    id        int unsigned  not null primary key auto_increment,
    timestamp datetime      not null,
    value     decimal(8, 2) not null,
    name      varchar(50)   not null,
    category  varchar(50)   not null
);

drop table if exists expense_categories;

create table expense_categories
(
    id   int unsigned not null primary key auto_increment,
    name varchar(50)  not null
);