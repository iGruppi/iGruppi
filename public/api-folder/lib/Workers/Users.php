<?php
class WorkerUser {

  static function userInfo($request, $response, $args) {
    Api::setPayload($request->getQueryParams());
    Api::checkUserToken();


    try {
        $userSessionVal = new Zend_Session_Namespace('userSessionVal');
        $gObj = new Model_Db_Users();
        $rec = $gObj->getUserByIdInGroup(Api::payload("id"), $userSessionVal->idgroup);
        if ($rec) {
            // remove password
            unset($rec->password);
            Api::result("OK", ["data" => Api::decorateRec("users", $rec)]);
        } else {
            Api::result("OK", ["data" => ""]);
        }
    } catch( Exception $e ) {
        Api::result("KO", ["error" => $e]);
    }
  }
}