<?php

  class UserGateway {
    private mysqli $database;

    public function __construct($database) {
      $this -> database = $database;
    }

    public function findAll() {
      $query = "SELECT * FROM users;";

      try {
        $result = $this -> database -> query($query);

        $rows = [];

        foreach ($result as $row) {
          array_push($rows, $row);
        }

        return $rows ?? [];
      } catch (Exception $e) {
        return $e;
      }
    }

    public function findOne($id) {
      $query = "SELECT * FROM users WHERE id = $id";

      try {
        $result = $this -> database -> query($query);

        $rows = [];

        foreach ($result as $row) {
          array_push($rows, $row);
        }

        return $rows ?? [];
      } catch (Exception $e) {
        return $e;
      }
    }

    public function findByName($name) {
      $query = "SELECT * FROM users WHERE name = '$name'";

      try {
        $result = $this -> database -> query($query);

        $rows = [];

        foreach ($result as $row) {
          array_push($rows, $row);
        }

        return $rows ?? [];
      } catch (Exception $e) {
        return $e;
      }
    }

    public function insert($name) {
      $query = "INSERT INTO users (name) VALUES('$name');";

      try {
        $alreadyExists = $this -> findByName($name);

        if($alreadyExists) {
          return new Exception('This user already exists', 409);
        }

        $this -> database -> query($query);

        $id = $this -> database -> insert_id;

        $user = $this -> findOne($id);

        return $user;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function update($id, $name) {
      $query = "UPDATE users SET name = '$name' WHERE id = '$id';";

      try {
        $user = $this -> findOne($id);

        if(!$user) {
          return new Exception('This user does not exists', 404);
        }

        $this -> database -> query($query);

        $user = $this -> findOne($id);

        return $user;
      } catch (Exception $e) {
        return $e;
      }
    }
  }