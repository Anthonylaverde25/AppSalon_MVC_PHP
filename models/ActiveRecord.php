<?php
namespace Model;
class ActiveRecord {

    // Base DE DATOS
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    // Alertas y Mensajes
    // los metodos y propiedades protegidos son heredados por las clases hijas
    protected static $alertas = [];
    
    // Definir la conexión a la BD - includes/database.php
    // Cuando es static se emplea self:: desde la clase hija para hacer referencia
    public static function setDB($database) {
        self::$db = $database;
    }

    public static function setAlerta($tipo, $mensaje) {
        static::$alertas[$tipo][] = $mensaje;
    }

    // Validación
    public static function getAlertas() {
        return static::$alertas;
    }

    public function validar() {
        static::$alertas = [];
        return static::$alertas;
    }

    // Consulta SQL para crear un objeto en Memoria
    public static function consultarSQL($query) {
        // recibe el $query el cual es una consulta
        // Consultar la base de datos
        $resultado = self::$db->query($query);


        // Se crea un array vacio para almacenar los resultados de la consulta
        $array = [];

        // Iterar los resultados obtenidos en la consulta
        while($registro = $resultado->fetch_assoc()) {

            // Para cada resultado obtenido se ejecuta  crearObjeto y asi convertir el registro en un objeto
            // Cada objeto que se crea que se agrega al array $array[]
            $array[] = static::crearObjeto($registro);
        }

        // liberar la memoria
        $resultado->free();

        // retornar los resultados (Retorna el array del objeto resultante)
        return $array;
    }



    // Crea el objeto en memoria que es igual al de la BD
    // recibe un registro (en forma de array asociativo)
    protected static function crearObjeto($registro) {

        // Se crea una nueva instacia de la clase actual
        // En este caso la clase actual es donde se llame (ejemplo $usuario = Usuario::where);
        // Por lo tanto se crea una nueva instancia de la clase usuario
        $objeto = new static;

        // Se intera a travez de las claves y valores del registro
        foreach($registro as $key => $value ) {

            // Si la propiedad key (clave) existe en la instancia del objeto actual ($objeto)
            // Se asigna el valor (value) correspondiente del registro a esa propiedad
            if(property_exists( $objeto, $key  )) {

                // ejemplo... Usuario->nombre = "antony";
                $objeto->$key = $value;
            }
        }

        // El objeto modificado se devuelve al final
        return $objeto;
    }

    // Identificar y unir los atributos de la BD
    public function atributos() {
        $atributos = []; // Se crea un array para almacenar los atributos y sus valores.
    
        // Se recorren las columnas de la tabla en la base de datos.
        foreach(static::$columnasDB as $columna) {
            if($columna === 'id') continue; // Si la columna es 'id', se omite y se pasa a la siguiente iteración.
    
            // Se asigna al array $atributos el nombre de la columna como clave y el valor correspondiente del objeto.
            $atributos[$columna] = $this->$columna;
        }
    
        return $atributos; // Se devuelve el array con los atributos y sus valores.
    }

    // Sanitizar los datos antes de guardarlos en la BD
    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value ) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    // Sincroniza BD con Objetos en memoria
    public function sincronizar($args=[]) { 
        foreach($args as $key => $value) {
          if(property_exists($this, $key) && !is_null($value)) {

            //$this->$key = $value; // Esto sería equivalente a $this->nombre = "John";
            $this->$key = $value;
          }
        }
    }

    // Registros - CRUD
    public function guardar() {
        $resultado = '';
        if(!is_null($this->id)) {
            // actualizar
            $resultado = $this->actualizar();
        } else {
            // Creando un nuevo registro
            $resultado = $this->crear();
        }
        return $resultado;
    }

    // Todos los registros
    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Busca un registro por su id
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla  ." WHERE id = {$id}";
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }

    // Obtener Registros con cierta cantidad
    public static function get($limite) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT {$limite}";
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }


    // Busca un registro 
    public static function where($columna,$valor) {
        //busca el registro
        $query = "SELECT * FROM " . static::$tabla  ." WHERE {$columna} = '{$valor}'";

        // consultaSQL ejecuta la consulta
        // esta retorna un array de objetos con la consulta

        $resultado = self::consultarSQL($query);
        // array_shift() en PHP se utiliza para eliminar y devolver el primer elemento de un array. 
        return array_shift( $resultado ) ;
    }

    // Consulta plana SQL (utilizar cuando los metodos del modelo no son suficientes)
    public static function SQL($query) {
        $resultado = self::consultarSQL($query);
        return $resultado;
        
    }


    // crea un nuevo registro
    public function crear() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Insertar en la base de datos
        $query = " INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES (' "; 
        $query .= join("', '", array_values($atributos));
        $query .= " ') ";

        // Resultado de la consulta
        $resultado = self::$db->query($query);
        return [
           'resultado' =>  $resultado,
           'id' => self::$db->insert_id
        ];
    }

    // Actualizar el registro
    public function actualizar() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Iterar para ir agregando cada campo de la BD
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }

        // Consulta SQL
        $query = "UPDATE " . static::$tabla ." SET ";
        $query .=  join(', ', $valores );
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 "; 

        // Actualizar BD
        $resultado = self::$db->query($query);
        return $resultado;
    }

    // Eliminar un Registro por su ID
    public function eliminar() {
        $query = "DELETE FROM "  . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);
        return $resultado;
    }

}