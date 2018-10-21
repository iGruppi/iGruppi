<?php

class Api
{
    static $payload = [];
    static $user = [];

    static function result($status, $retval = [])
    {
        $ret = [];
        $ret["status"] = $status;
        foreach ($retval as $k => $v) {
            $ret[$k] = $v;
        }
        // TODO:: look for format parameter in payload and serve xml
        header("Access-Control-Allow-Origin: *");
        header('Content-Type: application/json');
        echo json_encode($ret);
        exit;
    }

    static function setMeta($table, $id, $fieldName, $fieldValue)
    {
        $db = Zend_registry::get("db");
        $sql = "SELECT * from meta where tableid=:id and field=:field and tablename=:table";
        $checkSth = $db->prepare($sql);
        $checkSth->execute(array('id' => $id, 'table' => $table, "field" => $fieldName ));
        if ($checkSth->rowCount() > 0) {
            // Record found, update it
            $sql = "UPDATE meta set val=:val where tableid=:id and field=:field and tablename=:table";
        } else {
            $sql = "INSERT INTO meta (tableid,tablename,field,val) VALUES (:id, :table, :field, :val)";
            // Record not found, update it
        }
        $checkSth = $db->prepare($sql);
        $checkSth->execute(array('id' => $id, 'table' => $table, "field" => $fieldName, "val" => $fieldValue ));
    }

    static function getMeta($table, $id, $fieldName = "")
    {
        $ret = [];
        $db = Zend_registry::get("db");

        $ret = [];

        if ($fieldName) {
            $sql = "SELECT * from meta where tableid=:id and field=:field and tablename=:table";
            $checkSth = $db->prepare($sql);
            $checkSth->execute(array('id' => $id, 'table' => $table, "field" => $fieldName ));
        } else {
            $sql = "SELECT * from meta where tableid=:id and tablename=:table";
            $checkSth = $db->prepare($sql);
            $checkSth->execute(array('id' => $id, 'table' => $table ));
        }
        if ($checkSth->rowCount() > 0) {
            if ($fieldName) {
                $row = $checkSth->fetch(PDO::FETCH_OBJ);
                $ret = $row->val;
            } else {
                $ret = [];
                while ($row = $checkSth->fetch(PDO::FETCH_OBJ) ){
                    $ret[$row->field] = $row->val;
                }
            }
        }
        return $ret;
    }

    static function deleteMeta($table, $id, $fieldName)
    {
        $db = Zend_registry::get("db");
        $sql = "DELETE from meta where tableid=:id and field=:field and tablename=:table";
        $checkSth = $db->prepare($sql);
        $checkSth->execute(array('id' => $id, 'table' => $table, "field" => $fieldName ));
    }

    static function setPayload($request)
    {
        self::$payload = $request;
    }

    static function payload($key = "")
    {
        if ($key) {
            if (isset(self::$payload[$key])) {
                return self::$payload[$key];
            }
            return "";
        }
        return self::$payload;
    }

    static function setUser($u)
    {
        self::$user = $u;
    }

    static function getUserField($key)
    {
        if ($key) {
            if (isset(self::$user->{$key})) {
                return self::$user->{$key};
            }
            return "";
        }
        return self::$user;
    }

    static function checkUserToken($token = "")
    {
        if ($token == "") {
            $token = self::payload("token");
        }
        if ($token == "" && isset($_SERVER['HTTP_BEARER'])) {

            $token = $headerStringValue = $_SERVER['HTTP_BEARER'];
        }
        $parts = explode("-", $token);
        $id = $parts[0];

        $sql = "SELECT u.*, ug.attivo, ug.fondatore, ug.contabile, g.nome AS gruppo, g.idgroup "
            . "FROM users AS u LEFT JOIN users_group AS ug ON u.iduser=ug.iduser "
            . "LEFT JOIN groups AS g ON ug.idgroup=g.idgroup "
            . "WHERE u.iduser= :id";
        $db = Zend_registry::get("db");
        $checkSth = $db->prepare($sql);
        $checkSth->execute(array('id' => $id));
        if ($checkSth->rowCount() > 0) {
            // Fetch User data
            $row = $checkSth->fetch(PDO::FETCH_OBJ);
            // store user values
            $auth = Zend_Auth::getInstance();
            $auth->clearIdentity();
            $storage = $auth->getStorage();
            // remove password & write data to the store
            unset($row->password);
            $storage->write($row);

            // set idgroup in session
            $userSessionVal = new Zend_Session_Namespace('userSessionVal');
            $userSessionVal->idgroup = $row->idgroup;

            // set ACL User in session
            $aclUserObj = new Model_AclUser($row->fondatore, $row->contabile);
            $userSessionVal->aclUserObject = $aclUserObj;
            Api::setUser($row);
        }
        // TODO:: validate user on db
        if (!Api::getUserField("iduser")) {
            self::result("KO", ["error" => "Token not found or invalid"]);
        }
        return true;
    }

    static function decorateRec($tablename, $data)
    {
        $data = json_decode(json_encode($data), true);
        $id = "";
        $addCoords = false;
        switch( $tablename ){
            case "users":
                $id = $data["iduser"];
                $addCoords = true;
                break;
            case "produttori":
                $id = $data["idproduttore"];
                $addCoords = true;
                break;
            case "groups":
                $id = $data["idgroup"];
                $addCoords = true;
                break;
        }
        if ($id) {
            if ($addCoords) {
                $lat = 44.697904;
                $lng = 10.40003;
                $mult1 = (($id % 7) / 7) * (($id % 2) * -1);
                $mult2 = (($id % 9) / 9) * (($id % 2) * -1);
                $data["lat"] = $lat + (0.2 * $mult1);
                $data["lng"] = $lng + (0.2 * $mult2);
                $data["meta"] = Api::getMeta($tablename, $id);
            }
        }
        return $data;
    }
}