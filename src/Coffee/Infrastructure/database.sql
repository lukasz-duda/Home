drop table if exists coffee_machine;

create table coffee_machine
(
    id            boolean           not null primary key auto_increment,
    last_cleaned date not null,
    last_degreased date not null,
    last_lubricated date not null
) engine = InnoDB;

insert into coffee_machine (last_cleaned, last_degreased, last_lubricated)
values ('2001-02-03', '2001-02-03', '2001-02-03');