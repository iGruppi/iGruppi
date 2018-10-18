<?php
class WorkerUser {

  static function userInfo($request, $response, $args) {
    Api::setPayload($request->getQueryParams());
    Api::checkUserToken();

    $gObj = new Model_Db_Users();
    $rec = $gObj->getUserById(Api::payload("id"));
    Api::result("OK", ["data" => $rec]);
  }
}