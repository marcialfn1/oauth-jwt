<?php

$controller = ucwords($controller);
$controllerFile = "Controllers/" . $controller . ".php";

if (file_exists($controllerFile)) {
    require_once($controllerFile);
    // Crear la instancia de manera dinamica
    // Es como crear una instancia de la siguiente manera: $controller = new Home();
    $controller = new $controller();

    if (method_exists($controller, $method)) {
        $controller->{$method}($params);
    } else {
        require_once("Controllers/Error.php");
    }
} else {
    require_once("Controllers/Error.php");
}

?>