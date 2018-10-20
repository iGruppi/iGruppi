<?php

class WorkerProducts {

    static function products($request, $response, $args) {
        Api::setPayload($request->getQueryParams());
        Api::checkUserToken();

        $gObj = new Model_Db_Produttori();
        $groups = $gObj->getProduttori();
        Api::result("OK", ["data" => $groups]);
    }
    static function productInfo($request, $response, $args) {
        Api::setPayload($request->getQueryParams());
        Api::checkUserToken();

        $gObj = new Model_Db_Produttori();
        $rec = $gObj->getProduttoreById(Api::payload("id"));
        Api::result("OK", ["data" => $rec]);
    }
}