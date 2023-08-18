<?php

// Conectar a la base de datos a través de la clase ActiveRecord

// Importar la clase ActiveRecord para usarla en este archivo
use Model\ActiveRecord;




// Aquí comienza la parte de la autocarga de clases con Composer

// La siguiente línea carga automáticamente las clases y archivos de las dependencias del proyecto.
// __DIR__ representa el directorio actual del archivo en el que se encuentra esta línea.
// /../ nos lleva un nivel hacia arriba en la jerarquía de directorios, al directorio que contiene este archivo.
// 'vendor/autoload.php' completa la ruta hasta el archivo autoload.php en el directorio vendor,
// que es generado por Composer y maneja la autocarga de clases de las dependencias.
require __DIR__ . '/../vendor/autoload.php';


// Cargar las variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();



// Incluir funciones personalizadas
require 'funciones.php';

// Incluir la configuración de la base de datos
require 'database.php';



// Aquí termina la parte de la autocarga de clases con Composer


// Usar el método estático setDB() de la clase ActiveRecord para configurar la conexión a la base de datos
// Esto permite que otras partes del código utilicen esta conexión configurada para interactuar con la base de datos
ActiveRecord::setDB($db);

// A partir de aquí, puedes usar la conexión configurada en cualquier lugar del código
// para realizar operaciones en la base de datos usando la clase ActiveRecord

// ...
// Más código relacionado con el uso de la base de datos u otras funcionalidades
// ...

?>