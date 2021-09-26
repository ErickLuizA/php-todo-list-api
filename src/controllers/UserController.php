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
          $response = $this -> getAllUsers();
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

    private function getAllUsers() {
      $result = $this -> userGateway -> getAll();

      $response['status_code'] = 'HTTP/1.1 200';
      $response['body'] = json_encode($result);

      return $response;
    }
  }