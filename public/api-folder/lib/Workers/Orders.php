<?php

class WorkerOrders {

    static function orders($request, $response, $args) {
        Api::setPayload($request->getQueryParams());
        Api::checkUserToken();

        $gObj = new Model_Db_Produttori();
        $groups = $gObj->getProduttori();
        Api::result("OK", ["data" => $groups]);
    }
    static function orderInfo($request, $response, $args) {
        Api::setPayload($request->getQueryParams());
        Api::checkUserToken();

        $gObj = new Model_Db_Produttori();
        $rec = $gObj->getProduttoreById(Api::payload("id"));
        Api::result("OK", ["data" => $rec]);
    }
    static function orderDelivery($request, $response, $args) {
        Api::setPayload($request->getQueryParams());
        Api::checkUserToken();

        $gObj = new Model_Db_Prodotti();
        $rec = $gObj->getProdottiByIdProduttore(Api::payload("id"));
        Api::result("OK", ["data" => $rec]);
    }
}