<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
   public static function login(Router $router){

      $alertas = [];

      if($_SERVER['REQUEST_METHOD'] === "POST"){
         $auth = new Usuario($_POST);

         $alertas = $auth->validarLogin();

         if(empty($alertas)){
            // Comprobar que exista el usuario
            $usuario = Usuario::where('email', $auth->email);

            if($usuario){
               // Verificar el password
               if($usuario->comprobarPasswordAndVerificado($auth->password)){
                  // Autenticar el usuario

                  session_start();

                  $_SESSION['id'] = $usuario->id;
                  $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                  $_SESSION['email'] = $usuario->email;
                  $_SESSION['login'] = true;

                  // Redireccionamiento

                  if($usuario->admin === "1"){
                     $_SESSION['admin'] = $usuario->admin ?? null;

                     // Redireccionar a panel de administracion
                     header('Location: /admin');

                  }else{
                     // Redireccionar al apartado de citas para el cliente
                     header('Location: /cita');
                  }



               }
               
            }else{
               Usuario::setAlerta('error', 'Usuario no encontrado');
            }
         
         }

      };

      $alertas = Usuario::getAlertas();
      
      $router->render('auth/login', [
         'alertas' => $alertas
      ]);
   }


   public static function logout(){
      session_start();
      $_SESSION = [];
      header('Location: /');


   }


   public static function olvide(Router $router){

      $alertas = [];

      if($_SERVER['REQUEST_METHOD'] === 'POST'){
         $auth = new Usuario($_POST);
         $alertas = $auth->validarEmail();

         if(empty($alertas)){

            // Dado a la logica empleada para realizar la consulta, where me esta generando una nueva instancia
            $usuario = Usuario::where('email', $auth->email);

            if($usuario && $usuario->confirmado === "1"){

               // Gerenar un Token nuevo de un solo uso
               $usuario->crearToken();
               $usuario->guardar();


               // Enviar el email de recuperacion
               // Es muy importante respetar el orden el el cual se envian los datos ya que deben coincidir con su constructor
               $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
               $email->enviarInstrucciones();


               Usuario::setAlerta('exito', 'Revisa tu email');
            }else{

               // No es una instancia de la clase usuario, sino que es un llamado directo a uno de sus metodos heredados;
               Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
            }
         }
      }

      $alertas = Usuario::getAlertas();

      $router->render('auth/olvide-password',  [
         'alertas' => $alertas
      ]);
   }


   public static function recuperar(Router $router){

      $alertas = [];
      $error = false;
      $token = s($_GET['token']);

      $usuario = Usuario::where('token', $token);

      if(empty($usuario)){
         Usuario::setAlerta('error','Token no valido');
         $error = true;
      }

      if($_SERVER['REQUEST_METHOD'] === 'POST'){
         // Leer el nuevo password y guardarlo

         $password = new Usuario($_POST);
         $alertas = $password->validarPassword();


         if(empty($alertas)){

            // La contraseña se establece a null para luego poder ser reescrita
            $usuario->password = null;
      
            $usuario->password = $password->password;
            $usuario->hashPassword();
            $usuario->token = null;

            $resultado = $usuario->guardar();
            if($resultado){
               header('Location: /');
            }
         }
         
      }


      $alertas = Usuario::getAlertas();

      $router->render('auth/recuperar-password', [
         'alertas' => $alertas,
         'error' => $error
      ]);
     
   
   }

   public static function crear( Router $router){
      $alertas = [];
      $usuario = new Usuario;
      
      if($_SERVER['REQUEST_METHOD'] === "POST" ){
         $usuario->sincronizar($_POST);
         $alertas = $usuario->validarNuevaCuenta();

         // Revisar que las alertas esten vacias; de esta manera se entiende que paso todas las validaciones
         if(empty($alertas)){
           $resultado = $usuario->existeUsuario();

           if($resultado->num_rows){
            // retorna $alertas 
            // Verificar si el usuario ya se encuentra registrado
            $alertas = Usuario::getAlertas();
           }else{

            // creando un hash de la contraseña enviada por el usuario
            $usuario->hashPassword();

            // Creando un Token unico
            $usuario->crearToken();

            // Enviar el email
            $email = new Email($usuario->email,$usuario->nombre, $usuario->token);

            $email->enviarConfirmacion();

            $resultado = $usuario->guardar();

            if($resultado){
               header('Location: /mensaje');
            }
           }
         }
      }
      $router->render("auth/crear-cuenta", [
         "usuario" => $usuario,
         "alertas" => $alertas
      ]);
   
   }

   public static function confirmar(Router $router){

      // Inicializas el array de alertas vacio
      $alertas = [];

      $token = s($_GET['token']);
      $usuario = Usuario::where('token', $token);

      if(empty($usuario)){
         // Mostrar mensaje de error
         // Creo las alertas en memoria
         Usuario::setAlerta('error', 'Token no valido');

      }else{
         // Modificando a usuario confirmado

         // Usuario confirmado
         $usuario->confirmado = "1";

         // Se reescribe el token de tal manera que ningun otro usuario pueda hacer tramites con el mismo token
         $usuario->token = null;

         // Guardar (en este caso se ejecutara actualizar dentro del metodo guardar)
         $usuario->guardar();
         Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');

      }

      // Retorno las alertas
      $alertas = Usuario::getAlertas();

      // Renderizar la vista
      $router->render('auth/confirmar-cuenta', [
         'alertas' => $alertas
      ]);
   }

   
   public static  function mensaje(Router $router){
      $router->render('auth/mensaje');
   }

   
}