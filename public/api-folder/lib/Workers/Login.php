<?php

class WorkerLogin {

  static function login($request, $response, $args) {
    Api::setPayload($request->getQueryParams());
    $user = ["id"=>37];
    Api::result("OK", ["token" => sprintf( "%04d-%08d", $user["id"], abs(rand(1,100000000)-100000000))]);
  }
}