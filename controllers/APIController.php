<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController {

  // Método para obtener una lista de servicios
  public static function index(){
    // Se obtienen todos los servicios utilizando el método estático "all()" de la clase Servicio
    $servicios = Servicio::all();
    echo json_encode($servicios); // Se devuelve la lista de servicios en formato JSON
  }

  // Método para guardar una cita y sus servicios relacionados
  public static function guardar(){
    // Crea una nueva instancia de Cita utilizando los datos recibidos por POST
    $cita = new Cita($_POST);
    // Guarda la cita y obtiene el resultado (que incluye el ID generado)
    $resultado = $cita->guardar();
    $id = $resultado['id']; // Se obtiene el ID de la cita guardada
    
    // Se obtienen los IDs de los servicios seleccionados a partir de la cadena recibida por POST
    $idServicios = explode(",", $_POST['servicios'] );

    // Se recorren los IDs de los servicios para crear y guardar las relaciones Cita-Servicio
    foreach ($idServicios as $idServicio) {
      $args = [
        'citaId' => $id,
        'servicioId' => $idServicio
      ];

      // Se le pasa el $args como argumento a la nueva instancia de CitaServicio, y esta la toma el contructor de la clase
      $citaServicio = new CitaServicio($args); // Se crea una nueva instancia de CitaServicio
      $citaServicio->guardar(); // Se guarda la relación entre la cita y el servicio
    }

    // Se devuelve una respuesta en formato JSON con el resultado del proceso
    echo json_encode(['resultado' => $resultado]);
  }

  public static function eliminar(){

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

      $id = $_POST['id'];
      $cita = Cita::find($id);
      $cita->eliminar();
      header('Location:'. $_SERVER["HTTP_REFERER"]);



    }
  
  }
 
}
?>
