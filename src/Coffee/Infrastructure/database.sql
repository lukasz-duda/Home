drop table if exists coffees;

create table coffees
(
    current       smallint unsigned not null,
    last_cleaning smallint unsigned not null
);

insert into coffees (current, last_cleaning)
values (0, 0);