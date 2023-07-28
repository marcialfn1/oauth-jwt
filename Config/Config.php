<?php

const BASE_URL = "http://localhost/auth-jwt/";

// Datos de conexion a la base de datos
const CONNECTION = true;
const DB_HOST = "localhost";
const DB_NAME = "db_auth";
const DB_USER = "root";
const DB_PASSWORD = "";
const DB_CHARSET = "utf8";

/* Constante para encriptar y desencriptar el token, si la constante se crea despues de haber generado la KEY y se asigna otro valor a esta constante
entonces habra un error de desencriptacion para el decode(JWT), LO RECOMENDABLE ES CREAR PRIMERO EL const Y DESPUES HACER EL encode y decode*/
// const KEY_JWT = "password";


?>
