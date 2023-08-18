<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina">Coloca tu nueva contraseña</p>

<?php include_once __DIR__.'/../templates/alertas.php';?>

<?php if($error === true) return; ?> 
<form class="formulario" method="POST">
    <!-- no empleo el action para que se envie a este mismo sin modificar el token-->
    <div class="campo">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Tu nueva contraseña">
    </div>
    <input type="submit" class="boton" value="Guardar nueva contraseña">
</form>

<div class="acciones">
    <a href="/">ya tienes una cuenta? Inicia session</a>
    <a href="/crear-cuenta">Crear cuenta</a>
</div>