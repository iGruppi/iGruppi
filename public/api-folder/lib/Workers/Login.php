<?php

class WorkerLogin {

  static function login($request, $response, $args) {
    Api::setPayload($request->getQueryParams());
    $sql = "SELECT * FROM users WHERE email= :email";
    $db = Zend_registry::get("db");
    $checkSth = $db->prepare($sql);
    $checkSth->execute(array('email' => Api::payload("email")));
    if( $checkSth->rowCount() > 0 ) {
      // Fetch User data
      $row = $checkSth->fetch(PDO::FETCH_OBJ);
      if( $row->password == Api::payload("passwd") ){
          Api::setUser($row);
          Api::result("OK", ["token" => sprintf( "%04d-%08d", $row->iduser, abs(rand(1,100000000)-100000000))]);
      }
    }
    Api::result("KO", ["error" => "login: invalid email or password"]);
  }
}