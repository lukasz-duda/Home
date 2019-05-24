drop table if exists flat_expense;

create table flat_expense
(
    timestamp datetime      not null,
    name      varchar(80),
    value     decimal(8, 2) not null,
    person    varchar(10)   not null
);