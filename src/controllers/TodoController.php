<?php
  // Config issue gone wrong, fix this later! - include_path
  require "{$_SERVER['DOCUMENT_ROOT']}/../src/gateways/TodoGateway.php";

  class TodoController {
    private $requestMethod;
    private $userId;
    private $todoId;
    private $todoGateway;

    public function __construct($requestMethod, $userId, $todoId, $database) {
      $this -> requestMethod = $requestMethod;
      $this -> userId = $userId;
      $this -> todoId = $todoId;
      $this -> todoGateway = new todoGateway($database);
    }

    public function handle() {
      switch ($this -> requestMethod) {
        case 'GET':
          if(isset($this -> userId)) {
            if($this -> userId === 0) {
              $response = $this -> handleException(new Exception('Invalid parameter!', 422));
              break;
            }

            $response = $this -> getUserTodos($this -> userId);
            break;
          }

          if(isset($this -> todoId)) {
            if($this -> todoId === 0) {
              $response = $this -> handleException(new Exception('Invalid parameter!', 422));
              break;
            }

            $response = $this -> getTodo($this -> todoId);
            break;
          }

          $response = $this -> getTodos();
          break;
        case 'POST':
          if(!isset($this -> userId)) {
            $response = $this -> handleException(new Exception('Resource not found!', 501));
            break;
          }

          if($this -> userId === 0) {
            $response = $this -> handleException(new Exception('Invalid parameter!', 422));
            break;
          }

          $response = $this -> createTodo($this -> userId);
          break;
        case 'PUT':
          if(!isset($this -> todoId)) {
            $response = $this -> handleException(new Exception('Resource not found!', 501));
            break;
          }

          if($this -> todoId === 0) {
            $response = $this -> handleException(new Exception('Invalid parameter!', 422));
            break;
          }

          $response = $this -> updateTodo($this -> todoId);
          break;
        case 'DELETE':
          if(!isset($this -> todoId)) {
            $response = $this -> handleException(new Exception('Resource not found!', 501));
            break;
          }

          if($this -> todoId === 0) {
            $response = $this -> handleException(new Exception('Invalid parameter!', 422));
            break;
          }

          $response = $this -> deleteTodo($this -> todoId);
          break;
        default:
          $response = $this -> handleException(new Exception('Resource not found!', 501));
      }

      header($response['status_code']);
      if(isset($response['body'])) {
        echo $response['body'];
      }
    }

    private function getTodos() {
      $result = $this -> todoGateway -> findAll();

      if($result instanceof Exception) {
        return $this -> handleException($result);
      }

      $response['status_code'] = 'HTTP/1.1 200';
      $response['body'] = json_encode($result);

      return $response;
    }

    private function getUserTodos($userId) {
      $result = $this -> todoGateway -> findAllByUserId($userId);

      if($result instanceof Exception) {
        return $this -> handleException($result);
      }

      $response['status_code'] = 'HTTP/1.1 200';
      $response['body'] = json_encode($result);

      return $response;
    }

    private function getTodo($id) {
      $result = $this -> todoGateway -> findOne($id);

      if($result instanceof Exception) {
        return $this -> handleException($result);
      }

      $response['status_code'] = 'HTTP/1.1 200';
      $response['body'] = json_encode($result);

      return $response;
    }

    private function createTodo($userId) {
      $requestBody = json_decode(file_get_contents('php://input'));

      if(!isset($requestBody -> title) || !isset($requestBody -> description)) {
        return $this -> handleException(new Exception('Invalid request body', 422));
      }
      
      $result = $this -> todoGateway -> insert($requestBody -> title, $requestBody -> description, isset($requestBody -> done) ? $requestBody -> done : false, $userId);

      if($result instanceof Exception) {
        return $this -> handleException($result);
      }

      $response['status_code'] = 'HTTP/1.1 201';
      $response['body'] = json_encode($result);

      return $response;
    }

    private function updateTodo($todoId) {
      $requestBody = json_decode(file_get_contents('php://input'));

      if(!isset($requestBody -> title) && !isset($requestBody -> description) && !isset($requestBody -> done)) {
        return $this -> handleException(new Exception('Invalid request body', 422));
      }

      $associative_request = json_decode(json_encode($requestBody), true);

      $toUpdateRequest = array_map(function($key, $value) {
        return "$key = '$value'";
      }, array_keys($associative_request), array_values($associative_request));

      $result = $this -> todoGateway -> update(implode(',', $toUpdateRequest), $todoId);

      if($result instanceof Exception) {
        return $this -> handleException($result);
      }

      $response['status_code'] = 'HTTP/1.1 200';
      $response['body'] = json_encode($result);

      return $response;
    }

    private function deleteTodo($todoId) {      
      $result = $this -> todoGateway -> delete($todoId);

      if($result instanceof Exception) {
        return $this -> handleException($result);
      }

      $response['status_code'] = 'HTTP/1.1 204';
      $response['body'] = null;

      return $response;
    }

    private function handleException($exception) {
      $error = ["error" => $exception -> getMessage()];

      $response['status_code'] = "HTTP/1.1 {$exception -> getCode()}";
      $response['body'] = json_encode($error);

      return $response;
    }
  }