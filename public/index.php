<?php
  require '../bootstrap.php';
  require '../src/controllers/UserController.php';
  require '../src/controllers/TodoController.php';

  $endpoints = ['users', 'todos'];

  // Server Side Rendering
  // RESTFUL API
  

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $uri = explode('/', $uri);

  $endpoint = $uri[1];
  $userId = null;
  $todoId = null;

  if(isset($uri[2])) {
    $userId = (int) $uri[2];
  }

  if(!in_array($endpoint, $endpoints)) {
    header("HTTP/1.1 404");

    $response = ["error" => "Resource not found!"];

    exit(json_encode($response));
  }

  $requestMethod = $_SERVER['REQUEST_METHOD'];

  if($endpoint === $endpoints[0]) {
    $controller = new UserController($requestMethod, $userId, $databaseConnection);
    
    $controller -> handle();
  }

  $userId = null;

  if(isset($_SERVER['QUERY_STRING'])) {

    parse_str($_SERVER['QUERY_STRING'], $params);

    if(isset($params['userId'])) {
      $userId = $params['userId'];
    }
  }

  if(isset($uri[2])) {
    $todoId = (int) $uri[2];
  }

  if($endpoint === $endpoints[1]) {
    $controller = new TodoController($requestMethod, $userId, $todoId, $databaseConnection);
    
    $controller -> handle();
  }
