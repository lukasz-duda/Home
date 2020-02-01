drop table if exists knowledge_items;

create table knowledge_items
(
    id       int unsigned  not null primary key auto_increment,
    header   varchar(250)  not null,
    content  varchar(4000) not null,
    keywords varchar(250)  not null,
    date     date          not null
);