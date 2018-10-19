<?php

class WorkerGroup {

  static function groups($request, $response, $args) {
    Api::setPayload($request->getQueryParams());
    Api::checkUserToken();

    $gObj = new Model_Db_Groups();
    $data = $gObj->getAll();
    if(!is_null($data) && is_array($data) && count($data) > 0) {
        foreach($data AS &$group) {
            unset($group->email_ml);
        }
    } else {
        $data = [];
    }

    Api::result("OK", ["data" => $data]);
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