<main class="contenedor seccion">
    <h1>Crear Blog</h1>

    <?php foreach($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" action="/blogs/crear" enctype="multipart/form-data">
        <?php include "formulario.php"; ?>

        <input type="submit" value="Crear Blog" class="boton boton-verde no-margin-bottom">
    </form>

    <a href="/admin" class="boton boton-verde no-margin-bottom">Volver</a>
</main>