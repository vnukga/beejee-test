CREATE TABLE administrators (
    login varchar(100) not null unique,
    password_hash varchar(512) not null unique,
);