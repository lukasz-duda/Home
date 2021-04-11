drop table if exists flat_expense;

create table flat_expense
(
    id        int unsigned  not null primary key auto_increment,
    timestamp datetime      not null,
    name      varchar(80)   not null,
    value     decimal(8, 2) not null,
    person    varchar(10)   not null
) engine = InnoDB;