<?php

class WorkerGroup {

  static function groups($request, $response, $args) {
    Api::setPayload($request->getQueryParams());
    Api::checkUserToken();

    $gObj = new Model_Db_Groups();
    $groups = $gObj->getAll();
    Api::result("OK", ["data" => $groups]);
  }
  static function groupInfo($request, $response, $args) {
    Api::setPayload($request->getQueryParams());
    Api::checkUserToken();

    $gObj = new Model_Db_Groups();
    $rec = $gObj->getGroupById(Api::getUserField("idgroup"));
    Api::result("OK", ["data" => $rec]);
  }
  static function groupUsers($request, $response, $args) {
    Api::setPayload($request->getQueryParams());
    Api::checkUserToken();

    $gObj = new Model_Db_Groups();
    $rec = $gObj->getGroupFoundersById(Api::getUserField("idgroup"));
    Api::result("OK", ["data" => $rec]);
  }
}