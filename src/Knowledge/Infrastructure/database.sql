drop table if exists knowledge_items;

create table knowledge_items
(
    id        int unsigned not null primary key auto_increment,
    parent_id int unsigned,
    header    varchar(250) not null,
    content   varchar(4000),
    keywords  varchar(250) not null,
    date      date         not null
);