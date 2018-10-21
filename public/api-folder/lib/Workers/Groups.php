<?php

class WorkerGroup {

  static function groups($request, $response, $args) {
    Api::setPayload($request->getQueryParams());
    // Api::checkUserToken();

    $gObj = new Model_Db_Groups();
    $data = $gObj->getAll();
    if(!is_null($data) && is_array($data) && count($data) > 0) {
        foreach($data AS &$group) {
            unset($group->email_ml);
        }
    } else {
        $data = [];
    }
    $newr = [];
    foreach( $data as $r ) {
      $newr[] = Api::decorateRec("groups", $r);
    }
    Api::result("OK", ["data" => $newr]);
  }
  static function groupInfo($request, $response, $args) {
    Api::setPayload($request->getQueryParams());
    Api::checkUserToken();

    $gObj = new Model_Db_Groups();
    $rec = $gObj->getGroupById(Api::getUserField("idgroup"));

    Api::result("OK", ["data" => Api::decorateRec("groups", $rec)]);
  }
  static function groupUsers($request, $response, $args) {
    Api::setPayload($request->getQueryParams());
    Api::checkUserToken();

    $gObj = new Model_Db_Groups();
    $rec = $gObj->getGroupFoundersById(Api::getUserField("idgroup"));

    $newr = [];
    foreach( $rec as $r ) {
        $newr[] = Api::decorateRec("groups", $r);
    }
    Api::result("OK", ["data" => $newr]);
  }
}