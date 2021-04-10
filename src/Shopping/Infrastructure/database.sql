drop table if exists refund_plan;
drop table if exists expenses;
drop table if exists expense_categories;
drop table if exists shopping_list;

create table expense_categories
(
    id   tinyint unsigned not null primary key auto_increment,
    name varchar(50)      not null
) engine = InnoDB;

create table expenses
(
    id          int unsigned     not null primary key auto_increment,
    timestamp   datetime         not null,
    value       decimal(8, 2)    not null,
    name        varchar(50)      not null,
    category_id tinyint unsigned not null,
    foreign key (category_id) references expense_categories (id)
) engine = InnoDB;

create index ix_expenses on expenses (timestamp desc);

create table refund_plan
(
    expense_id    int unsigned not null,
    foreign key (expense_id) references expenses (id),
    for_me        bool         not null,
    transfer_date datetime     null
) engine = InnoDB;

create index ix_refund_plan on refund_plan (transfer_date);

create table shopping_list
(
    json varchar(4000) not null
) engine = InnoDB;

insert into expense_categories (name)
values ('Jedzenie');

insert into shopping_list (json)
values ('[]');