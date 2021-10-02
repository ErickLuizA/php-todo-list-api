<?php
  // Config issue gone wrong, fix this later! - include_path
  require "{$_SERVER['DOCUMENT_ROOT']}/../src/gateways/UserGateway.php";

  class UserController {
    private $requestMethod;
    private $userGateway;

    public function __construct($requestMethod, $database) {
      $this -> requestMethod = $requestMethod;
      $this -> userGateway = new UserGateway($database);
    }

    public function handle() {
      switch ($this -> requestMethod) {
        case 'GET':
          $response = $this -> getUsers();
          break;
        case 'POST':
          $response = $this -> createUser();
          break;
        default:
          $response['status_code'] = 'HTTP/1.1 400';
          $response['body'] = null;
      }

      header($response['status_code']);
      if($response['body']) {
        echo $response['body'];
      }
    }

    private function getUsers() {
      $result = $this -> userGateway -> findAll();

      if($result instanceof Exception) {
        return $this -> handleException($result);
      }

      $response['status_code'] = 'HTTP/1.1 200';
      $response['body'] = json_encode($result);

      return $response;
    }

    private function createUser() {
      $requestBody = json_decode(file_get_contents('php://input'));

      if(!isset($requestBody -> name)) {
        return $this -> handleException(new Exception('Invalid request body', 422));
      }
      
      $result = $this -> userGateway -> insert($requestBody -> name);

      if($result instanceof Exception) {
        return $this -> handleException($result);
      }

      $response['status_code'] = 'HTTP/1.1 201';
      $response['body'] = json_encode($result);

      return $response;
    }

    private function handleException($exception) {
      $error = ["error" => $exception -> getMessage()];

      $response['status_code'] = "HTTP/1.1 {$exception -> getCode()}";
      $response['body'] = json_encode($error);

      return $response;
    }
  }