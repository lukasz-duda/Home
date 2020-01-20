drop table if exists expenses;

create table expenses
(
    id          int unsigned  not null primary key auto_increment,
    timestamp   datetime      not null,
    value       decimal(8, 2) not null,
    name        varchar(50)   not null,
    category_id int unsigned  not null
);

drop table if exists expense_categories;

create table expense_categories
(
    id   int unsigned not null primary key auto_increment,
    name varchar(50)  not null
);

drop table if exists refund_plan;

create table refund_plan
(
    expense_id    int unsigned not null,
    for_me        bool         not null,
    transfer_date datetime     null
);

drop table if exists shopping_list;

create table shopping_list
(
    json varchar(4000) not null
);

insert into shopping_list (json)
values ('[]');