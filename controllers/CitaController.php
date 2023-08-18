<?php

namespace Controllers;

use MVC\Router;

class CitaController {

    // Las funciones estaticas pueden ser llamadas sin la necesidad de crear una nueva instancia de la clase en la cual se encuentra
    // Puede ser util en situaciones en donde solo se requiere llamar a un metodo o etc

   public static function index (Router $router){

    // Al incluir session_start() en este controlador tendre acceso a los datos de session que se crearon en el controlador LoginController
    // session_start(); inicializa o reanuda una sesión, y una vez que la sesión está activa, puedes acceder a las variables de sesión en cualquier parte del código siempre y cuando se haya llamado a session_start(); previamente.

    //session_start();
    //debuguear($_SESSION);

    isAuth();
    $router->render('cita/index',[
        'nombre' => $_SESSION['nombre'],
        'id' => $_SESSION['id']
    ]);
   }
}

