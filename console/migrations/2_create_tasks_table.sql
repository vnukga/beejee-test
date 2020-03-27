CREATE TABLE tasks (
                       id integer not null auto_increment primary key ,
                       name varchar(100) not null,
                       email varchar(255) not null,
                       text text not null,
                       is_closed bool not null default false,
                       is_edited bool not null default false
)