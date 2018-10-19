<?php
class WorkerUser {

  static function userInfo($request, $response, $args) {
    Api::setPayload($request->getQueryParams());
    Api::checkUserToken();

    $userSessionVal = new Zend_Session_Namespace('userSessionVal');
    $gObj = new Model_Db_Users();
    $rec = $gObj->getUserByIdInGroup(Api::payload("id"), $userSessionVal->idgroup);
    if ($rec) {

        // remove password
        unset($rec->password);

        Api::result("OK", ["data" => $rec]);
    } else {
        Api::result("KO", ["error" => "Data not found!"]);
    }

  }
}