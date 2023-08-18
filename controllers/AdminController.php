<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController {

    public static function index(Router $router) {
        // Es abministrador
        isAdmin();

        // Obtener el valor del parámetro 'fecha' de la URL mediante GET, si está presente. 
        // Si no está presente, se establecerá la fecha actual en formato 'Y-m-d'.
        $fecha = $_GET['fecha'] ?? date('Y-m-d');

        // Dividir la fecha en sus componentes (año, mes y día) utilizando el guión como delimitador.
        $fechas = explode("-", $fecha);

        // Verificar si los componentes de la fecha son válidos utilizando la función checkdate.
        // Los componentes de la fecha se obtienen del arreglo $fechas.
        if (!checkdate($fechas[1], $fechas[2], $fechas[0])) {
            // Si la fecha no es válida, redirigir a la página de error 404.
            header('Location: /404');
        }

        // Consulta a la base de datos
        $consulta = "SELECT citas.id, citas.hora, CONCAT(usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= "usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio ";
        $consulta .= "FROM citas ";
        $consulta .= "LEFT OUTER JOIN usuarios ";
        $consulta .= "ON citas.usuarioId = usuarios.id ";
        $consulta .= "LEFT OUTER JOIN citasServicios ";
        $consulta .= "ON citasServicios.citaId = citas.id ";
        $consulta .= "LEFT OUTER JOIN servicios ";
        $consulta .= "ON servicios.id = citasServicios.servicioId ";
        $consulta .= "WHERE fecha = '{$fecha}' ";

        $citas = AdminCita::SQL($consulta);

        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha 
        ]);
    }
}
