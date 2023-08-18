<?php

namespace MVC;

class Router
{
    // Arrays para almacenar rutas y funciones controladoras para solicitudes GET y POST
    public array $getRoutes = [];    // Almacenar rutas y funciones controladoras para solicitudes GET
    public array $postRoutes = [];   // Almacenar rutas y funciones controladoras para solicitudes POST

    // Agregar una ruta y función controladora para solicitudes GET
    public function get($url, $fn)
    {
        $this->getRoutes[$url] = $fn; // Asignar la función controladora a la ruta específica para GET
    }

    // Agregar una ruta y función controladora para solicitudes POST
    public function post($url, $fn)
    {
        $this->postRoutes[$url] = $fn; // Asignar la función controladora a la ruta específica para POST
    }

    // Comprobar y manejar las rutas
    public function comprobarRutas()
    {
        // Iniciar la sesión para proteger ciertas rutas
        session_start();

        // Obtener la URL actual y el método de solicitud (GET o POST)
        $currentUrl = strtok($_SERVER['REQUEST_URI'], '?') ?? '/'; // Obtener la parte de la URL antes de "?"
        $method = $_SERVER['REQUEST_METHOD']; // Obtener el método de la solicitud (GET o POST)

        // Determinar si existe una función controladora para la ruta y el método
        if ($method === 'GET') {
            $fn = $this->getRoutes[$currentUrl] ?? null; // Obtener la función controladora correspondiente a la ruta GET
        } else {
            $fn = $this->postRoutes[$currentUrl] ?? null; // Obtener la función controladora correspondiente a la ruta POST
        }

        // Si se encuentra una función controladora, ejecutarla con "$this" como argumento
        if ($fn) {
            call_user_func($fn, $this); // Ejecutar la función controladora con "$this" como argumento
        } else {
            echo "Página No Encontrada o Ruta no válida";
        }
    }

    // Renderizar una vista con datos opcionales
    public function render($view, $datos = [])
    {
        // Asignar variables dinámicamente para la vista
        foreach ($datos as $key => $value) {
            $$key = $value; // Crear variables con nombres de clave del arreglo y asignarles valores
        }

        ob_start(); // Iniciar un buffer de salida para almacenar el contenido

        // Incluir la vista en el diseño y almacenar la salida
        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean(); // Limpiar el buffer y obtener su contenido

        // Incluir el contenido de la vista en el diseño final
        include_once __DIR__ . '/views/layout.php';
    }
}
