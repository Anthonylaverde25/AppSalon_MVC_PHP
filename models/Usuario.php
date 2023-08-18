<?php

namespace Model;


class Usuario extends ActiveRecord {
    protected static $tabla = "usuarios";
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password','telefono','admin','confirmado','token'];

    // al ser publicas pueden ser accedidas desde la clase misma o sus intancias
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args=[])
    {
        $this->id = $args["id"] ?? null;
        $this->nombre = $args["nombre"] ?? "";
        $this->apellido = $args["apellido"] ?? "";
        $this->email = $args["email"] ?? "";
        $this->password = $args["password"] ?? "";
        $this->telefono = $args["telefono"] ?? "";
        $this->admin = $args["admin"] ?? "0";
        $this->confirmado = $args["confirmado"] ?? "0";
        $this->token = $args["token"] ?? "";

    }


    /* Mensajes de validacion para la creacion de cuentas */
    public function validarNuevaCuenta(){
        if(!$this->nombre){
            // self:: indica que es un metodo o propiedad estatica, entiendo que indica que es estatica para si por lo tanto aunque sea eredado del padre hace referencia a la clase hija (esta)
            self::$alertas['error'][] = 'El nombre es necesario.';
        }

        if(!$this->apellido){
            self::$alertas['error'][] = 'El apellido es necesario';
        }

        if(!$this->telefono){
            self::$alertas['error'][] = 'El numero de telefono es necesaio';
        }

        if(!$this->email){
            self::$alertas['error'][]= 'El correo es necesario';
        }

        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña es requerida';
        }

        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'La contraseña debe tener al menos 6 caracteres';
        }


       

        return self::$alertas;
    }


    public function validarLogin(){

        if(!$this->email){
            self::$alertas['error'][] = 'El email es Obligatorio';

        }

        if(!$this->password){
            self::$alertas['error'][] = 'El Password es Obligatorio';

        }

        return self::$alertas;
    }


    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El correo es Obligatorio';

        }

        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][]='La contraseña es obligatoria';
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'][]='El password debe ser mayor a 6 caracteres';

        }

        return self::$alertas;
    }

    // Revisar si el usuario ya existe
    public function existeUsuario(){
        $query = " SELECT * FROM ". self::$tabla . " WHERE email = '". $this->email ."' LIMIT 1";

        $resultado = self::$db->query($query);
        if($resultado->num_rows){
            self::$alertas['error'][] = 'El usuario ya esta registrado';
        }

        return $resultado;
    }

    // Hashear Password
    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Crear Token
    public function crearToken(){
        // La función uniqid() en PHP se utiliza para generar un identificador único basado en el tiempo actual
        $this->token = uniqid();
    }


    // comprobar que el passwprod y el verificado
    public function comprobarPasswordAndVerificado($password){
        $resultado = password_verify($password, $this->password);

        if(!$resultado || !$this->confirmado){
            self::$alertas['error'][] = 'Password incorrecto o tu cuenta no a sido verificada';
        }else {
            return true;
        }

    }

 
    


}