<?php
// Requerir una vez el archivo autoload.php para hacer uso de JWT
require_once "Libraries/jwt/vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Oauthjwt extends Controllers {
    public function __construct() {
        parent::__construct();
    }

    public function registroCliente() {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "POST") {
                $_POST = json_decode(file_get_contents('php://input'), true);

                if (empty($_POST['nombre']) or !testString($_POST['nombre'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los Nombre(s)'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['apellido']) or !testString($_POST['apellido'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los Apellidos'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['email']) or !testEmail($_POST['email'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en el Email'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['password'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Password es requerido'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                $strNombre = ucwords(strClean($_POST['nombre']));
                $strApellido = ucwords(strClean($_POST['apellido']));
                $strEmail = strClean($_POST['email']);
                $strPassword = hash("SHA256", $_POST['password']);

                $request = $this->model->setCliente(
                    $strNombre,
                    $strApellido,
                    $strEmail,
                    $strPassword
                );

                if ($request > 0) {
                    $arrUser = array(
                        'id' => $request
                    );
                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos registrados correctamente',
                        'content-data' => $arrUser
                    );
                } else {
                    $response = array(
                        'status' => true,
                        'mensaje' => 'El Email ya esta registrado',
                    );
                }

                $code = 200;
            } else {
                $response = array(
                    'status' => false,
                    'mensaje' => 'Error en la solicitud' . ' ' . $method
                );
                $code = 400;
            }

            jsonResponse($response, $code);
            die();
        } catch (Exception $e) {
            echo "Error en el proceso" . $e->getMessage();
        }
        die();
    }

    public function crearApp() {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "POST") {
                $_POST = json_decode(file_get_contents('php://input'), true);

                if (empty($_POST['email']) or !testEmail($_POST['email'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en el Email'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['name_app'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Nombre de la Aplicacion es requerida'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                $strEmail = strClean($_POST['email']);
                $strApp = strClean($_POST['name_app']);
                
                $arrCliente = $this->model->getCliente($strEmail);

                if (empty($arrCliente)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El usuario no existe'
                    );
                } else {
                    $idclientejwt = $arrCliente['idcliente_jwt'];
                    $nombreCompleto = $arrCliente['nombre'] . ' ' . $arrCliente['apellido'];
                    $strNameApp = $strEmail . ' ' . $strApp;

                    // Se encripta para obtener le client_id y key_secret
                    $client_id = hash("SHA256", $nombreCompleto) . '-' . hash("SHA256", $strNameApp);
                    $key_secret = hash("SHA256", $strNameApp) . '-' . hash("SHA256", $nombreCompleto);

                    $request = $this->model->setApp(
                        $strApp,
                        $client_id,
                        $key_secret,
                        $idclientejwt
                    );

                    if ($request > 0) {
                        $arrApp = array(
                            'id' => $request,
                            'email' => $strEmail,
                            'client_id' => $client_id,
                            'key_secret' => $key_secret,
                            'name' => $strApp
                        );

                        $response = array(
                            'status' => true,
                            'mensaje' => 'Datos guardados correctamente',
                            'content-data' => $arrApp
                        );
                    } else {
                        $response = array(
                            'status' => false,
                            'mensaje' => 'La aplicacion ya existe',
                        );
                    }
                }

                $code = 200;
            } else {
                $response = array(
                    'status' => false,
                    'mensaje' => 'Error en la solicitud' . ' ' . $method
                );
                $code = 400;
            }

            jsonResponse($response, $code);
            die();
        } catch (Exception $e) {
            echo "Error en el proceso" . $e->getMessage();
        }
        die();
    }

    public function token() {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "POST") {
                // dep($_SERVER);exit; // Verificar que esta devolviendo la variable $_SERVER
                // Tras ingresar el Username & Password en Auth
                if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Autorizacion requerida'
                    );

                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['grant_type']) || $_POST['grant_type'] != 'client_credentials') {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los parametros'
                    );

                    jsonResponse($response, 200);
                    die();
                }

                // Guardar en dos variables tanto el $client_id y el $key_password
                $client_id = $_SERVER['PHP_AUTH_USER'];
                $key_secret = $_SERVER['PHP_AUTH_PW'];

                $request = $this->model->getAppAuth(
                    $client_id,
                    $key_secret
                );

                if ($request > 0) {
                    // Almacenar en variables la inf. que se necesitara para generar el token
                    $idAppName = $request['id_app'];
                    $nameApp = $request['name_app'];
                    $idClienteJWT = $request['idcliente_jwt'];
                    $email = $request['email'];
                    $time = time();
                    
                    // Especificar el tiempo de expiracion del token
                    /*
                    1 M = 60
                    1 H = 3600
                    1 D = 86,400
                    */
                    // $expires_in = $time + (60 * 60 * 24); // 1 dia 0 24 hrs
                    $expires_in = $time + (3600); // 1 hra
                    // $expires_in = date('Y-m-d H:i:s', $expires_date);

                    // Se define el PayLoad, es el array que solicita el JWT (documentacion)
                    // iat (fecha de creacion)
                    $arrPayLoad = array(
                        'id_app' => $idAppName,
                        'name_app' => $nameApp,
                        'email' =>  $email,
                        'iat' => $time,
                        // 'expiration' => $expires_in
                        'exp' => $expires_in
                    );

                    // La constante KEY_JWT esta declarada en el archivo config.php, se cambio el KEY_JWT
                    // Se usara para el $key_secret de la app del cliente registrado para proteger la integridad del token y los datos
                    $tokenJWT = JWT::encode($arrPayLoad, $key_secret, 'HS512');

                    $insertToken = $this->model->setTokenDB($idClienteJWT, $idAppName, $tokenJWT, $expires_in);

                    if ($insertToken > 0) {
                        $arrData = array(
                            'id' => $idAppName,
                            'access_token' => $tokenJWT,
                            'token' => "Bearer",
                            'expire_in' => $expires_in,
                            'app' => $nameApp
                        );

                        $response = array(
                            'status' => true,
                            'mensaje' => 'Token generado correctamente',
                            'content-data' => $arrData
                        );
                    } else {
                        $response = array(
                            'status' => false,
                            'mensaje' => 'Error al registrar el Token, consulte al administrador'
                        );
                    }
                } else {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error de autenticacion'
                    );
                }

                $code = 200;
            } else {
                $response = array(
                    'status' => false,
                    'mensaje' => 'Error en la solicitud' . ' ' . $method
                );
                $code = 400;
            }

            jsonResponse($response, $code);
            die();
        } catch (Exception $e) {
            echo "Error en el proceso" . $e->getMessage();
        }
        die();
    }

    public function tokenValidate($token) {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "GET") {
                if (empty($token)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Token de autorizacion es obligatorio'
                    );

                    jsonResponse($response, 200);
                    die();
                }

                $tokenJWT = strClean($token);
                $request = $this->model->getToken($tokenJWT);

                if (empty($request)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Token no es valido'
                    );
                } else {
                    $key_secret = $request['key_secret'];
                    $token = $request['access_token'];
                    $time = $request['expires_in'];
                    $arrData = array(
                        'access_token' => $token,
                        'token_type' => "Bearer",
                        'expire_in' => $time
                    );
                    $decoded = JWT::decode($token, new Key($key_secret, 'HS512'));

                    $response = array(
                        'status' => true,
                        'mensaje' => 'Token valido',
                        'content-data' => $arrData
                    );
                }

                $code = 200;
            } else {
                $response = array(
                    'status' => false,
                    'mensaje' => 'Error en la solicitud' . ' ' . $method
                );
                $code = 400;
            }

            jsonResponse($response, $code);
            die();
        } catch (Exception $e) {
            $response = array(
                'status' => false,
                'mensaje' => $e->getMessage()
            );

            jsonResponse($response, 200);
        }
        die();
    }
}
?>