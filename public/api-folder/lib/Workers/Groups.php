<?php

class WorkerGroup {

  static function lista($request, $response, $args) {
    Api::setPayload($request->getQueryParams());
    Api::checkUserToken();

    $gObj = new Model_Db_Groups();
    $groups = $gObj->getAll();
    Api::result("OK", ["data" => $groups]);
  }

}