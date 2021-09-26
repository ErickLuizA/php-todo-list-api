<?php

  class UserGateway {
    private mysqli $database;

    public function __construct($database) {
      $this -> database = $database;
    }

    public function getAll() {
      $query = "SELECT * FROM users;";

      try {
        $result = $this -> database -> query($query);

        $rows = [];

        foreach ($result as $row) {
          array_push($rows, $row);
        }

        return $rows ?? [];
      } catch (Exception $e) {
        error_log($e -> getMessage());
      }
    }
  }