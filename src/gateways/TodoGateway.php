<?php

  class TodoGateway {
    private mysqli $database;

    public function __construct($database) {
      $this -> database = $database;
    }

    public function findAll() {
      $query = "SELECT * FROM todos WHERE deleted_at IS NULL";

      try {
        $result = $this -> database -> query($query);

        $rows = [];

        foreach ($result as $row) {
          array_push($rows, $row);
        }

        return $rows;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function findAllByUserId($userId) {
      $query = "SELECT * FROM todos WHERE user_id = $userId AND deleted_at IS NULL";

      try {
        $result = $this -> database -> query($query);

        $rows = [];

        foreach ($result as $row) {
          array_push($rows, $row);
        }

        return $rows;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function findOne($id) {
      $query = "SELECT * FROM todos WHERE id = $id AND deleted_at IS NULL";

      try {
        $result = $this -> database -> query($query);

        $rows = [];

        foreach ($result as $row) {
          array_push($rows, $row);
        }

        return sizeof($rows) ? $rows[0] : [];
      } catch (Exception $e) {
        return $e;
      }
    }

    public function insert($title, $description, $done, $userId) {
      $query = "INSERT INTO todos (title, description, done, user_id) VALUES('$title', '$description', '$done', '$userId');";

      try {
        $userExists = $this -> findUser($userId);

        if(!$userExists) {
          return new Exception('User does not exists', 422);
        }

        $this -> database -> query($query);

        $id = $this -> database -> insert_id;

        $todo = $this -> findOne($id);

        return $todo;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function update($request, $todoId) {
      $query = "UPDATE todos SET $request WHERE id = $todoId;";
      
      try {
        $todo = $this -> findOne($todoId);

        if(!$todo) {
          return new Exception('This todo does not exists', 404);
        }

        $this -> database -> query($query);

        $updatedTodo = $this -> findOne($todoId);

        return $updatedTodo;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function delete($id) {
      $query = "UPDATE todos SET deleted_at = CURRENT_TIMESTAMP WHERE id = $id;";

      try {
        $todo = $this -> findOne($id);

        if(!$todo) {
          return new Exception('This todo does not exists', 404);
        }

        $this -> database -> query($query);

        return;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function findUser($id) {
      $query = "SELECT * FROM users WHERE id = $id AND deleted_at is null";

      try {
        $result = $this -> database -> query($query);

        $rows = [];

        if(!$result) return [];

        foreach ($result as $row) {
          array_push($rows, $row);
        }

        return $rows;
      } catch (Exception $e) {
        return $e;
      }
    }
  }