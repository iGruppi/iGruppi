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
      if (isset(self::$payload[$key])) {
        return self::$payload[$key];  
      }
      return "";
    }
    return self::$payload;
  }
  
  static function setUser( $u ) {
    self::$user = $u;
  }
  
  static function getUserField( $key ) {
    if( $key ) {
      if (isset(self::$user->{$key})) {
        return self::$user->{$key};  
      }
      return "";
    }
    return self::$user;
  }
  
  static function checkUserToken( $token = "" ) {
    if( $token == "" ){
      $token = self::payload("token");
    }
      if( $token == "" ){

          $token = $headerStringValue = $_SERVER['HTTP_BEARER'];
      }
      $parts = explode("-",$token);
    $id = $parts[0];
    
  $sql = "SELECT u.*, ug.attivo, ug.fondatore, ug.contabile, g.nome AS gruppo, g.idgroup "
                    ."FROM users AS u LEFT JOIN users_group AS ug ON u.iduser=ug.iduser "
                    ."LEFT JOIN groups AS g ON ug.idgroup=g.idgroup "
                    ."WHERE u.iduser= :id";
                    $db = Zend_registry::get("db");
              $checkSth = $db->prepare($sql);
              $checkSth->execute(array('id' => $id));
              if( $checkSth->rowCount() > 0 ) 
              {
                  // Fetch User data
                  $row = $checkSth->fetch(PDO::FETCH_OBJ);
                  // store user values
                  $auth = Zend_Auth::getInstance();
                  $auth->clearIdentity();
                  $storage = $auth->getStorage();
                  // remove password & write data to the store
                  unset($row->password);
                  $storage->write($row);

                  // set idgroup in session
                  $userSessionVal = new Zend_Session_Namespace('userSessionVal');
                  $userSessionVal->idgroup = $row->idgroup;
                  
                  // set ACL User in session
                  $aclUserObj = new Model_AclUser($row->fondatore, $row->contabile);
                  $userSessionVal->aclUserObject = $aclUserObj;
                  Api::setUser($row);
              }
    // TODO:: validate user on db 
    if ( !Api::getUserField("iduser")) {
      self::result("KO", "Token not found or invalid");
    }
    return true;
  }
}