<?php

  class Database {
    protected $connection;

    public function __construct() {
      $host = $_ENV['DB_HOST'];
      $user = $_ENV['DB_USERNAME'];
      $password = $_ENV['DB_PASSWORD'];
      $database = $_ENV['DB_DATABASE'];
      $port = $_ENV['DB_PORT'];

      try {
        $this -> connection = new mysqli($host, $user, $password, $database, $port);

        if (mysqli_connect_errno()) {
          throw new Exception("Error while trying to connect to the database.");
        }

        error_log("Connected to the database");
      } catch (Exception $e) {
        error_log($e -> getMessage());
      }
    }

    public function getConnection() {
      return $this -> connection;
    }
  }