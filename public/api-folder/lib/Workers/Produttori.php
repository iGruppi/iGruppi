<?php

class WorkerProduttori {
  static function produttori($request, $response, $args) {
    Api::setPayload($request->getQueryParams());
    Api::checkUserToken();

    $gObj = new Model_Db_Produttori();
    $groups = $gObj->getProduttori();

    Api::result("OK", ["data" => $groups]);
  }
  static function produttoriInfo($request, $response, $args) {
    Api::setPayload($request->getQueryParams());
    Api::checkUserToken();

    $gObj = new Model_Db_Produttori();
    $rec = $gObj->getProduttoreById(Api::payload("id"));
    Api::result("OK", ["data" => Api::decorateRec("produttori", $rec)]);
  }
  static function produttoriProducts($request, $response, $args) {
    Api::setPayload($request->getQueryParams());
    Api::checkUserToken();

    $gObj = new Model_Db_Prodotti();
    $rec = $gObj->getProdottiByIdProduttore(Api::payload("id"));
    Api::result("OK", ["data" => $rec]);
  }
}