<main class="contenedor seccion">
    <h1>Registrar Vendedor(a)</h1>

    <?php foreach($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>
    
    <form class="formulario" method="POST" action="/vendedores/crear" enctype="multipart/form-data">
        <?php include "formulario.php"; ?>

        <input type="submit" value="Registrar Vendedor(a)" class="boton boton-verde no-margin-bottom">
    </form>

    <a href="/admin" class="boton boton-verde no-margin-bottom">Volver</a>
</main>