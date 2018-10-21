<?php
class WorkerMeta
{
    static function metaSet($request, $response, $args)
    {
        Api::setPayload($request->getQueryParams());
        Api::checkUserToken();

        Api::setMeta(Api::payload("table"), Api::payload("id"), Api::payload("field"), Api::payload("value"));
        $meta = Api::getMeta(Api::payload("table"), Api::payload("id"), Api::payload("field"));
        Api::result("OK", ["data" => $meta] );
    }
    static function metaDelete($request, $response, $args)
    {
        Api::setPayload($request->getQueryParams());
        Api::checkUserToken();

        Api::deleteMeta(Api::payload("table"), Api::payload("id"), Api::payload("field"));
        Api::result("OK", ["data" => []] );
        return;
    }
}