<?php

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

$connection->exec('CREATE TABLE likes (
  like uuid TEXT NOT NULL 
    CONSTRAINT uuid_primary_key PRIMARY KEY,
  post uuid TEXT NOT NULL 
    CONSTRAINT username_unique_key,
  author uuid TEXT NOT NULL 
    CONSTRAINT username_unique_key
)');