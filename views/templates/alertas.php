<?php
    // foreach($alertas as $key => $mensajes):: Este bucle foreach itera a través del array $alertas. Para cada iteración, toma la clave (key) y los valores (mensajes) del array. El símbolo => separa la clave y los valores

    foreach($alertas as $key => $mensajes):
        foreach($mensajes as $mensaje):

        
?>  
<div class="alerta <?php echo $key ?>"> 
            <?php echo $mensaje; ?>
</div>
<?php
  endforeach;
 endforeach;
?>