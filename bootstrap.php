<?php
  require 'vendor/autoload.php';
  require 'src/config/Database.php';

  use Dotenv\Dotenv;

  $dotenv = Dotenv::createImmutable(__DIR__);

  $dotenv -> load();

  $databaseConnection = (new Database()) -> getConnection();
