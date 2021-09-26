<?php
  require '../bootstrap.php';
  require '../src/controllers/UserController.php';

  $endpoints = ['users', 'todos'];

  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $uri = explode('/', $uri);

  $endpoint = $uri[1];

  if(!in_array($endpoint, $endpoints)) {
    header("HTTP/1.1 404");

    $response = ["error" => "Resource not found!"];

    exit(json_encode($response));
  }

  $requestMethod = $_SERVER['REQUEST_METHOD'];

  if($endpoint === $endpoints[0]) {
    $controller = new UserController($requestMethod, $databaseConnection);

    $controller -> handle();
  }

  if($endpoint === $endpoints[1]) {
    header("HTTP/1.1 404");

    $response = ["error" => "Resource not implemented yet!"];

    exit(json_encode($response));
  }
