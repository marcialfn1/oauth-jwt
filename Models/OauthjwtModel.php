<?php

class OauthjwtModel extends Mysql {
    private $intIdCliente;
    private $strNombre;
    private $strApellido;
    private $strEmail;
    private $strPassword;
    private $strNameApp;
    private $strClienteID;
    private $strKey;
    private $intAppId;
    private $strToken;
    private $strExpiration;
    public function __construct() {
        parent::__construct();
    }

    public function setCliente($nombre, $apellido, $email, $password) {
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->strEmail = $email;
        $this->strPassword = $password;

        // dep(get_object_vars($this));

        $sql = "SELECT email FROM cliente_jwt WHERE email = '$this->strEmail' AND status != 0";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $sql = "INSERT INTO cliente_jwt(nombre, apellido, email, password) VALUES(:nom, :ape, :mail, :pass)";

            $arrData = array(
                ":nom" => $this->strNombre,
                ":ape" => $this->strApellido,
                ":mail" => $this->strEmail,
                ":pass" => $this->strPassword
            );

            $request = $this->insert($sql, $arrData);
            return $request;
        } else {
            return false;
        }
    }

    public function getCliente($email) {
        $this->strEmail = $email;

        $sql = "SELECT idcliente_jwt, nombre, apellido, email FROM cliente_jwt WHERE email = :mail AND status != 0";
        $arrData = array(
            ":mail" => $this->strEmail
        );

        $request = $this->select($sql, $arrData);
        return $request;
    }

    public function setApp($nameapp, $cliente_id, $keysecret, $idclientejwt) {
        $this->strNameApp = $nameapp;
        $this->strClienteID = $cliente_id;
        $this->strKey = $keysecret;
        $this->intIdCliente = $idclientejwt;

        $sql = "SELECT * FROM app_jwt WHERE name_app = '$this->strNameApp' AND clientejwt_id = '$this->intIdCliente' AND status != 0";
        $request = $this->select_all($sql);

        if (count($request) > 0) {
            return false;
        } else {
            $sql = "INSERT INTO app_jwt(name_app, client_id, key_secret, clientejwt_id) VALUES(:n_app, :c_id, :key, :c_jwt)";

            $arrData = array(
                ":n_app" => $this->strNameApp,
                ":c_id" => $this->strClienteID,
                ":key" => $this->strKey,
                ":c_jwt" => $this->intIdCliente
            );

            $request = $this->insert($sql, $arrData);
            return $request;
        }
    }

    public function getAppAuth($client_id, $key_secret) {
        $this->strClienteID = $client_id;
        $this->strKey = $key_secret;

        // dep(get_object_vars($this));

        $sql = "SELECT a.id_app, a.name_app, c.idcliente_jwt, c.email  FROM app_jwt a 
        INNER JOIN cliente_jwt c
        ON a.clientejwt_id = c.idcliente_jwt WHERE a.client_id = BINARY :client_id AND a.key_secret = BINARY :key
        AND a.status != :status AND c.status != :status";

        $arrData = array(
            ":client_id" =>$this->strClienteID,
            ":key" => $this->strKey,
            ":status" => 0
        );
        $request = $this->select($sql, $arrData);
        return $request;
    }

    public function setTokenDB($clientejwt_id, $app_id, $token, $expiration) {
        $this->intIdCliente = $clientejwt_id;
        $this->intAppId = $app_id;
        $this->strToken = $token;
        $this->strExpiration = $expiration;

        // dep(get_object_vars($this));

        $sql = "INSERT INTO token_jwt(clientejwt_id, app_id, access_token, expires_in) VALUES(:idc_jwt, :appid, :access, :expire)";

        $arrData = array(
            ":idc_jwt" => $this->intIdCliente,
            ":appid" => $this->intAppId,
            ":access" => $this->strToken,
            ":expire" => $this->strExpiration
        );
        $request = $this->insert($sql, $arrData);
        return $request;
    }

    public function getToken($token) {
        $this->strToken = $token;

        $sql = "SELECT t.access_token, t.expires_in, a.key_secret FROM token_jwt t
        INNER JOIN app_jwt a
        ON t.app_id = a.id_app
        WHERE t.access_token = BINARY :access AND t.status != 0 AND a.status != 0";
        $arrData = array(
            ":access" => $this->strToken
        );

        $request = $this->select($sql, $arrData);
        // dep($request);exit;
        return $request;
    }
}


?>