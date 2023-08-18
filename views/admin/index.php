
<!-- Encabezado de la página -->
<h1 class="nombre-pagina">Desde el panel de administración</h1>

<!-- Título de la sección -->
<h2>Buscar Citas</h2>

<!-- Inclusión de una barra de navegación o plantilla -->
<?php include_once __DIR__ . '../../templates/barra.php';?>

<!-- Formulario de búsqueda de citas -->
<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha;?>" />
        </div>
    </form>
</div>

<?php
    if(count($citas)===0){
        echo "<H2>No hay citas para esta fecha</H2>";
    }
?>

<!-- Contenedor para mostrar las citas -->
<div id="citas-admin">
    <ul class="citas">
        <?php
        // Variable para controlar si ya se mostró la información de una cita
        $idCita = 0;
        
        // Iterar a través de las citas
        foreach ($citas as $key => $cita) {
            // Verificar si es una nueva cita
            if ($idCita !== $cita->id) {
                // Mostrar detalles básicos de la cita
                
                // Inicializando total a pagar
                $total = 0;
        ?>
        <li>
            <p>ID: <span><?php echo $cita->id; ?></span></p>
            <p>Hora: <span><?php echo $cita->hora; ?></span> </p>
            <p>Cliente: <span><?php echo $cita->cliente; ?></span> </p>
            <p>Email: <span><?php echo $cita->email; ?></span> </p>
            <p>Telefono: <span><?php echo $cita->telefono; ?></span> </p>
            
            <!-- Encabezado para mostrar servicios asociados a la cita -->
            <h3>Servicios</h3>
        </li>
        <?php
                // Actualizar el ID de la cita actual
                $idCita = $cita->id;
            } // FIN DEL IF

            // Acumular el precio del servicio al total
            $total += $cita->precio;
        ?>
        
        <!-- Mostrar los nombres de los servicios y sus precios -->
        <p class="servicios"><?php echo $cita->servicio . " " . $cita->precio; ?></p>

        <?php
            // Obtener el ID de la cita actual y la siguiente (si existe)
            $actual = $cita->id;
            $proximo = $citas[$key + 1]->id ?? "0";

            // Verificar si es el último servicio de la cita
            if (esUltimo($actual, $proximo)) { ?>
                <p class="total">Total: <span>$ <?php echo $total; ?></span></p>

                <form action="/api/eliminar" method="POST">
                    <input type="hidden" name="id" value="<?php echo $cita->id;?>">
                    <input class="boton-eliminar" type="submit" value="Eliminar">
                </form>
        <?php 
            }
        ?>
        
        <?php
            } // FIN DEL FOREACH
        ?>
    </ul>
</div>

<?php
    $script = "<script src='build/js/buscador.js'></script>"
?>
