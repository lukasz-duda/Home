drop table if exists expense;

create table expense
(
    id        int unsigned  not null primary key auto_increment,
    timestamp datetime      not null,
    value     decimal(8, 2) not null,
    name      varchar(50)   not null,
    category  varchar(50)   not null
)