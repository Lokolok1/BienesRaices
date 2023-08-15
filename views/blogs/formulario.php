<fieldset>
    <legend>Información General</legend>

        <label for="titulo">Titulo:</label>
        <input type="text" id="titulo" name="titulo" placeholder="Titulo Blog" value="<?php echo s($blog->titulo); ?>">

        <label for="autor">Autor:</label>
        <input type="text" id="autor" name="autor" placeholder="Autor Blog" value="<?php echo s($blog->autor); ?>">

        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

        <?php if ($blog->imagen): ?>
            <img src="/imagenes/<?php echo $blog->imagen ?>" class="imagen-small">
        <?php endif; ?>
    </fieldset>

    <fieldset>
        <legend>Información Blog</legend>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion"><?php echo s($blog->descripcion); ?></textarea>

        <label for="texto">Texto:</label>
        <textarea id="texto" name="texto"><?php echo s($blog->texto); ?></textarea>
    </fieldset>