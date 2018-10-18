<?php

class Api {
  static $payload = [];
  static $user = [];
  
  static function result( $status, $retval ) {
    if ($status === "OK") {
      $ret = ["status"=>"OK", "data" => $retval];
    } else {
      $ret = ["status"=>"KO", "error" => $retval];  
    }
    // TODO:: look for format parameter in payload and serve xml
    header('Content-Type: application/json');
    echo json_encode($ret);
    exit;
  }
  
  static function setPayload($request) {
    self::$payload = $request;
  }
  
  static function payload($key="") {
    if( $key ) {
      return self::$payload[$key];    
    }
    return self::$payload;
  }
  
  static function checkUserToken( $token = "" ) {
    if( $token == "" ){
      $token = self::payload("token");
    }
    $parts = explode("-",$token);
    $id = $parts[0];
    // TODO:: validate user on db 
    if ( floor($id) != 37) {
      self::result("KO", "User token not found");
    }
    self::$user = ["id" => $parts[0]];
    return true;
  }
}