<?php

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');


$connection->exec('CREATE TABLE users (
    uuid TEXT NOT NULL 
      CONSTRAINT uuid_primary_key PRIMARY KEY,
    username TEXT NOT NULL 
      CONSTRAINT username_unique_key UNIQUE,
    first_name TEXT,
    last_name TEXT
  )');

$connection->exec('CREATE TABLE posts (
  post uuid TEXT NOT NULL 
    CONSTRAINT uuid_primary_key PRIMARY KEY,
  author uuid TEXT NOT NULL 
    CONSTRAINT username_unique_key,
  title TEXT,
  txt TEXT
)');

$connection->exec('CREATE TABLE comments (
  comment uuid TEXT NOT NULL 
    CONSTRAINT uuid_primary_key PRIMARY KEY,
  post uuid TEXT NOT NULL 
    CONSTRAINT username_unique_key,
  author uuid TEXT NOT NULL 
    CONSTRAINT username_unique_key,
  txt TEXT
)');

$connection->exec('CREATE TABLE likes (
  like uuid TEXT NOT NULL 
    CONSTRAINT uuid_primary_key PRIMARY KEY,
  post uuid TEXT NOT NULL 
    CONSTRAINT username_unique_key,
  author uuid TEXT NOT NULL 
    CONSTRAINT username_unique_key
)');